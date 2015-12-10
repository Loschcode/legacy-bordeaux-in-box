<?php

function mailing_send($profile, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

  // We resolve everything
	$user = $profile->user()->first();
	$email = $user->email;

	$datas = [

		'email' => $email,
		'user' => $user,
		'profile' => $profile,
		'subject' => $subject,
		'template' => $template,
		'template_data' => $template_data,
		'additional_mailgun_variables' => $additional_mailgun_variables,

	];

	send_email_with_trace($datas);

}

function mailing_send_user_only($user, $subject, $template, $template_data, $additional_mailgun_variables=NULL) {

	$email = $user->email;

	$datas = [

		'email' => $email,
		'user' => $user,
		'subject' => $subject,
		'template' => $template,
		'template_data' => $template_data,
		'additional_mailgun_variables' => $additional_mailgun_variables,

	];

	send_email_with_trace($datas);

}

function send_email_with_trace($datas) {

	if (isset($datas['email'])) $email = $datas['email']; else $email = NULL;
	if (isset($datas['user'])) $user = $datas['user']; else $user = NULL;
	if (isset($datas['profile'])) $profile = $datas['profile']; else $profile = NULL;
	if (isset($datas['subject'])) $subject = $datas['subject']; else $subject = NULL;
	if (isset($datas['template'])) $template = $datas['template']; else $template = NULL;
	if (isset($datas['template_data'])) $template_data = $datas['template_data']; else $template_data = NULL;
	if (isset($datas['additional_mailgun_variables'])) $additional_mailgun_variables = $datas['additional_mailgun_variables']; else $additional_mailgun_variables = NULL;

	// We resolve the body for the email trace logs
	$body_preparation = View::make($template, $template_data);
	$body = $body_preparation->render();

	// We will queue the email (we could add a protection here)
	Mail::queue($template, $template_data, function($message) use ($email, $subject, $body, $user, $profile, $additional_mailgun_variables)
	{

		// We prepare the email trace
		$email_trace = new EmailTrace;
		$email_trace->recipient = $email;
		$email_trace->subject = $subject;

		if ($user !== NULL) $email_trace->user_id = $user->id;
		if ($profile !== NULL) $email_trace->user_profile_id = $profile->id;
		
		$email_trace->prepared_at = date('Y-m-d H:i:s');

		if ($profile !== NULL) $profile_id = $profile->id; else $profile_id = NULL;
		if ($user !== NULL) $user_id = $user->id; else $profile_id = NULL;

		$email_trace->content = $body;
		$email_trace->save();

		// We prepare the MailGun variables
		$mailgun_variables = [

			'user_id' => (int) $user_id,
			'profile_id' => (int) $profile_id,
			'email_trace_id' => (int) $email_trace->id,

		];

		// Is there any additional variable ?
		if ($additional_mailgun_variables !== NULL) $mailgun_variables += $additional_mailgun_variables;

		// We encode it
		$encoded_mailgun_variables = json_encode($mailgun_variables);

		// We finally send the email with all the correct headers
		$message->to($email)->subject($subject);
		$message->getHeaders()->addTextHeader('X-Mailgun-Variables', $encoded_mailgun_variables);
		
	});

}

function is_birthday($dateBirthday) {

	// It's an european date
	$dateBirthday = str_replace('/', '-', $dateBirthday);

	$birthday = \Carbon\Carbon::parse($dateBirthday);
	$now = \Carbon\Carbon::now('Europe/Paris');

	if ($birthday->month == $now->month)
	{
		return true;
	}

	return false;

}

function get_category_from_children_special_fields($age) {

	// From the oldest to the youngest
	$categories = array_reverse(Config::get('bdxnbx.children_special_fields'));

	foreach ($categories as $slug_category => $category) {

		if ($age >= $category['min_age']) return $slug_category;

	}

	return FALSE;

}

function get_category_from_date_age($age) {

	// From the oldest to the youngest
	$categories = array_reverse(Config::get('bdxnbx.date_age_special_fields'));

	foreach ($categories as $slug_category => $category) {

		if ($age >= $category['min_age']) return $slug_category;

	}

	return FALSE;

}

function convert_date_to_age($string) {

		$string = str_replace('/', '-', $string); // To be understood

		if (strlen($string) === 4) $string = $string.'-01-01'; // If it only a year, we add artificially the month and days

		$birthday = new DateTime($string);
		$interval = $birthday->diff(new DateTime);

		return $interval->y;
		
}

function convert_model_object_to_ids_array($object) {

	$final_array = [];
	foreach ($object as $datas) {

		array_push($final_array, (int) $datas['id']);

	}

	return $final_array;

}

/**
 * Will refresh the whole answers of the user depending on a specific question form
 * Everything is managed dynamically.
 */
function refresh_answers_from_dynamic_questions_form($fields, $profile) {

	// In case of edition we remove the old answers
	foreach ($profile->answers()->get() as $answer) {

		$answer->delete();

	}

	// We will generate the answers for the user
	foreach ($fields as $raw_id => $answer) {

		$arr_id = explode('-', $raw_id);
		$matching_question = BoxQuestion::find($arr_id[0]);

		// It means it's a valid answer (not _token or some other fields)
		if (isset($arr_id[1])) {

			/**
			 * Special case children_details
			 * This will be an array containing all the different replies for each children
			 * It will be treated completely differently by the system
			 *
			 * To work, the 'name' input must be in first place
			 * It will be considered as the referent for the other entries
			 * This is very manual and spaghetti but there's no other way right now.
			 * 
			 */
			if ($matching_question->type == 'children_details') {

				foreach ($answer as $kid) {

					foreach ($kid as $to_referent_slug => $real_answer) {

						// If anything else is filled, we can validate the name as unknown
						// -> It will match for the name in this case
						if (empty($real_answer) && ($to_referent_slug == 'child_name')) {

							$comparison = implode($kid);
							if ($comparison !== "000") $real_answer = "?";

						}

						// We make the name pretty
						if ($to_referent_slug == 'child_name') {

							$real_answer = ucfirst(strtolower($real_answer));

						}

						// Will work only if the answers are valid
						if (($real_answer != "0") && (!empty($real_answer))) {

							$user_answer = new UserAnswer;
							$user_answer->profile()->associate($profile);

							$box_question = $matching_question;
							$user_answer->box_question()->associate($box_question);

							// Every other answers from this will be linked with the parent 'name'
							// It's the master entry
							if (($to_referent_slug != 'child_name') && (isset($referent_id))) {

								$user_answer->referent_id = $referent_id;

							}

							$user_answer->to_referent_slug = $to_referent_slug;
							$user_answer->answer = $real_answer;
							$user_answer->save();

							if ($to_referent_slug == 'child_name') {

								$referent_id = $user_answer->id;

							}

						}

					}

					// For each kid we will get a new referent, we don't want to risk to have the same
					unset($referent_id);

				}

			} else {

				/**
				 * Classical system, nothing special compared to above
				 */
				if (!empty($answer)) {
					
					$user_answer = new UserAnswer;
					$user_answer->profile()->associate($profile);

					/**
					 * Date is kind of special too
					 * It has a to_referent_slug even tho there's no other field
					 * It's for the filter to work without SQL queries
					 */
					if ($matching_question->type == 'date') {

						$user_answer->to_referent_slug = 'date_age';

					}

					$box_question = $matching_question;
					$user_answer->box_question()->associate($box_question);

					$user_answer->answer = $answer;

					$user_answer->save();

				}

			}

		}

	}

}

function generate_children_birth_form($years=25) {

	$dropdown = ['0' => '-'];

	$year = date("Y") - $years;
	$dropdown[$year] = "Avant $year";

	$num = $years-1;
	while ($num > 0) {

		$year = date("Y") - $num;
		$dropdown[$year] = "De $year";
		$num--;

	}

	$year = date("Y");
	$dropdown[$year] = "De/Pour $year";

	$year = date("Y") + 1;
	$dropdown[$year] = "Pour $year";

	return $dropdown;

}

function generate_priority_form() {

	return [

		"low" => "Basse",
		"medium" => "Normale",
		"high" => "Elevée",
		
		];

}

function generate_unity_form() {

	return [

		"1" => "1 unité",
		"2" => "2 unité",
		"3" => "3 unité",
		"4" => "4 unité",
		"5" => "5 unité",
		"6" => "6 unité",
		"7" => "7 unité",
		"8" => "8 unité",
		"9" => "9 unité",
		"10" => "10 unité",
		
		];

}

function generate_percent_form() {

	return [

		"0" => "0%",
		"5" => "5%",
		"10" => "10%",
		"20" => "20%",
		"30" => "30%",
		"40" => "40%",
		"50" => "50%",
		"60" => "60%",
		"70" => "70%",
		"80" => "80%",
		"90" => "90%",
		"100" => "100%",

		];

}
function generate_month_form() {

	return [

		"0" => '-',
		"01" => 'en Janvier',
		"02" => 'en Février',
		"03" => 'en Mars',
		"04" => 'en Avril',
		"05" => 'en Mai',
		"06" => 'en Juin',
		"07" => 'en Juillet',
		"08" => 'en Août',
		"09" => 'en Septembre',
		"10" => 'en Octobre',
		"11" => 'en Novembre',
		"12" => 'en Décembre'

		];

}

function generate_children_sex() {

	$final = ["0" => 'Fille ou garçon ?'];
	$final += Config::get('bdxnbx.children_sex_fields');

	return $final;

}

function delete_file($file, $folder) {

	$file_path = public_path().'/uploads/'.$folder.$file;
	if (File::exists($file_path)) File::delete($file_path);

}

function upload_image($file, $folder, $table_class, $name, $attributes) {

	$destinationPath = 'public/uploads/'.$folder.'/';

	$filename = value(function() use ($file, $name) {

		$filename = uniqid() . Str::slug($name) . '.' . $file->getClientOriginalExtension();
		return $filename;

	});

	$file->move($destinationPath, $filename);

	$table_class->folder = $folder;
	$table_class->filename = $filename;

	foreach ($attributes as $attribute => $value) {

		$table_class->$attribute = $value;

	}
	
	return $table_class->save();

}

/**
 * Fetch randomly an array and take one entry
 * @param  array  $arr
 * @param  integer $num number of entries to select
 * @return mixed
 */
function array_random($arr, $num = 1) {

    shuffle($arr);
    
    $r = array();
    
    for ($i = 0; $i < $num; $i++) {
        $r[] = $arr[$i];
    }

    return $num == 1 ? $r[0] : $r;

}

function readable_payment_type($type) {

	if ($type == 'plan') return 'Abonnement';
	elseif ($type == 'direct_invoice') return 'Transfert unique';
	else return 'Inconnu';
	
}

function readable_payment_status($status) {

	if ($status) return 'Succès';
	else return 'Echec';

}

function readable_profile_priority($priority) {

	if ($priority === 'high') return 'Elevée';
	elseif ($priority === 'medium') return 'Normale';
	elseif ($priority === 'low') return 'Basse';
	else return 'N/A';

}

function readable_order_status($status) {

	if ($status == 'paid') return 'Payé';
	elseif ($status == 'unpaid') return 'Non payé';
	elseif ($status == 'scheduled') return 'Planifié';
	elseif ($status == 'failed') return 'Echec';
	elseif ($status == 'delivered') return 'Envoyé';
	elseif ($status == 'half-paid') return 'Partiellement payé';
	elseif ($status == 'packing') return 'En préparation';
	elseif ($status == 'ready') return 'Prêt pour envoi';
	elseif ($status == 'problem') return 'Problème';
	elseif ($status == 'canceled') return 'Annulé';

}

function convert_to_graph_colors($colors_array) {

	$final_array = [];

	foreach ($colors_array as $color) {

		if ($color == 'blue') array_push($final_array, '#0b62a4');
		elseif ($color == 'red') array_push($final_array, '#D64541');
		elseif ($color == 'green') array_push($final_array, '#1E824C');
		elseif ($color == 'purple') array_push($final_array, '#913D88');
		elseif ($color == 'black') array_push($final_array, '#2C3E50');
		elseif ($color == 'brown') array_push($final_array, '#96281B');
		elseif ($color == 'orange') array_push($final_array, '#E67E22');
		else array_push($final_array, $color);

	}

	return $final_array;

}

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_orders($orders) {

	$email_already_used = [];

	foreach ($orders as $order) {

		$profile = $order->user_profile()->first();

		if (($profile != NULL) && (!in_array($profile->user()->first()->email, $email_already_used))) {

			array_push($email_already_used, $profile->user()->first()->email);

		}

	}

	return $email_already_used;

}

/**
 * Get email listing from a got orders list (model object)
 * @param  object $orders
 * @return array with all the emails
 */
function get_email_listing_from_unfinished_profiles($series) {

	$email_already_used = [];

	foreach ($series->user_order_buildings()->get() as $user_order_building) {

		$profile = $user_order_building->profile()->first();

		if (($profile != NULL) && (!in_array($profile->user()->first()->email, $email_already_used))) {

			array_push($email_already_used, $profile->user()->first()->email);

		}

	}

	return $email_already_used;

}

/**
 * Get the percent of unfinished buildings on a given series
 * @param  object $serie 
 * @return integer
 */
function get_percent_unfinished_buildings($serie) {

    
    $raw_percent = $serie->user_order_buildings()->count() / ($serie->orders()->notCanceledOrders()->count() + $serie->user_order_buildings()->count());
    $percent = $raw_percent * 100;
    return round($percent);

}

function order_spot_or_destination_zip($order) {

	if ($order->take_away == TRUE) {

		$spot = $order->delivery_spot()->first();

		if ($spot != NULL) {

			$output = $spot->zip;

		} else {

			$output = '';

		}

	} else {

		$destination = $order->destination()->first();

		if ($destination != NULL) {

			$output = $destination->zip;

		} else {

			$output = '';

		}
	}

	return $output;

}

function order_spot_or_destination($order) {

	if ($order->take_away == TRUE) {

		$spot = $order->delivery_spot()->first();

		if ($spot != NULL) {

			$output = '<strong>'.$spot->name.'</strong><br />'.$spot->city.', '.$spot->zip.'<br />'.$spot->address.'<br />';

		} else {

			$output = 'Inconnue';

		}

	} else {

		$destination = $order->destination()->first();

		if ($destination != NULL) {

			$output = '<strong>'.$destination->last_name.' '.$destination->first_name.'</strong><br />'.$destination->city.', '.$destination->zip.'<br />'.$destination->address.'<br />';

		} else {

			$output = 'Inconnue';

		}
	}

	return $output;

}

function order_questions_and_answers($box, $profile, $spacer=", ") {

	$questions = $box->questions()->get();
	$output = '';

	foreach ($questions as $question) {

		$output .= $question->question.' - ';

		$answers = $profile->answers();
		$old_reply = $answers->where('box_question_id', $question->id);

		if ($question->type === "text") {

			if ($old_reply->first() != NULL)
			$output .= $old_reply->first()->answer;

		} elseif ($question->type === "textarea") {

			if ($old_reply->first() != NULL)
			$output .= $old_reply->first()->answer;

		} else {

			if ($question->answers()->first() == NULL) {

				$output .= 'Aucune';

			}

			foreach ($old_reply->get() as $answer) {

				$output .= $answer->answer.$spacer; 

			}

		}

		$output .= '<br />';

	}

	return $output;

}

function order_questions($box, $profile, $spacer=" - ") {

	$questions = $box->questions()->get();
	$output = '';

	foreach ($questions as $question) {

		
		if (empty($question->short_question)) $final_question = $question->question;
		else $final_question = $question->short_question;

		$output .= $final_question.$spacer;

		$output .= '<br />';

	}

	return $output;

}

function order_answers($box, $profile, $spacer=", ") {

	$questions = $box->questions()->get();
	$output = '';

	foreach ($questions as $question) {

		$answers = $profile->answers();
		$old_reply = $answers->where('box_question_id', $question->id);

		if ($question->type === "text") {

			if ($old_reply->first() != NULL)
			$output .= $old_reply->first()->answer;

		} elseif ($question->type === "textarea") {

			if ($old_reply->first() != NULL)
			$output .= $old_reply->first()->answer;

		} else {

			if ($question->answers()->first() == NULL) {

				$output .= 'Aucune';

			}

			foreach ($old_reply->get() as $answer) {

				$output .= $answer->answer.$spacer; 

			}

		}

		$output .= '<br />';

	}

	return $output;

}

/**
 * Get the slug from the service contact and output a readable label
 * @param  string $slug e.g. tech-idea, tech-bug
 * @return string
 */
function readable_contact_service($slug) {

	$arr = Config::get('bdxnbx.contact_service');

	if (strpos($slug, 'com-') !== FALSE) {

		$arr = $arr['Commercial'];

	} elseif (strpos($slug, 'tech-') !== FALSE) {

		$arr = $arr['Technique'];

	}

	if (isset($arr[$slug])) return $arr[$slug];
	else return 'Inconnu';

}

/**
 * Duplicate an existing order and apply the new order to the targeted series
 * @param   object $order
 * @param  object $delivery_serie
 * @return void
 */
function generate_new_order($user, $profile) {

	$last_order = $profile->orders()->orderBy('created_at', 'desc')->orderBy('orders.id', 'desc')->first();
	$last_delivery_serie = $last_order->delivery_serie()->first();

	$delivery_spot = $last_order->delivery_spot()->first();

	$delivery_serie = DeliverySerie::where('delivery', '>', $last_delivery_serie->delivery)->whereNull('closed')->orderBy('delivery', 'asc')->first();

	// We make the order
	$order = new Order;
	$order->user()->associate($user);
	$order->user_profile()->associate($profile);
	$order->delivery_serie()->associate($delivery_serie);
	$order->box()->associate($last_order->box()->first());

	// We don't lock the new orders
	$order->locked = FALSE;

	// If there's a spot (take away only)
	if ($delivery_spot !== NULL) $order->delivery_spot()->associate($delivery_spot);

	$order->status = 'scheduled';
	$order->gift = $last_order->gift;
	$order->take_away = $last_order->take_away;
	$order->unity_and_fees_price = $last_order->unity_and_fees_price;
	$order->save();

	// We make the order billing
	$order_billing = new OrderBilling;
	$order_billing->order()->associate($order);
	$order_billing->first_name = $user->first_name;
	$order_billing->last_name = $user->last_name;
	$order_billing->city = $user->city;
	$order_billing->address = $user->address;
	$order_billing->zip = $user->zip;
	$order_billing->save();

	$last_order_destination = $last_order->destination()->first();

	if ($last_order_destination != NULL) {

		// We make the order destination
		$order_destination = new OrderDestination;
		$order_destination->order()->associate($order);
		$order_destination->first_name = $last_order_destination->first_name;
		$order_destination->last_name = $last_order_destination->last_name;
		$order_destination->city = $last_order_destination->city;
		$order_destination->address = $last_order_destination->address;
		$order_destination->zip = $last_order_destination->zip;
		$order_destination->save();

	}

}

/**
 * Generate a CSV for the payments
 * @param  string $file_name the file name
 * @param  object $payments the payment object
 * @return csv  
 */
function generate_csv_payments($file_name, $payments)
{

	$output[0] = [

		'ID',
		'Stripe Customer',
		'Stripe Event',
		'Stripe Charge',
		'Stripe Card',
		'Utilisateur',
		'ID Abonnement',
		'Box',
		'Série',
		'Type',
		'Montant',
		'Statut',
		'Derniers chiffres de carte',
		'Date'

	];

	foreach ($payments as $payment) {

		// We prepare some stuff
		$profile = $payment->profile()->first();
		$user = $profile->user()->first();
		$box = $profile->box()->first();

		$email = $user->email;

		if ($box == NULL) $box_title = 'Non renseigné';
		else $box_title = $box->title;

		$amount = $payment->amount;

		if ($payment->order()->first() != NULL) {

			$serie = $payment->order()->first()->delivery_serie()->first()->delivery;

		} else {

			$serie = 'N/A';

		}

		$output[] = [

			$payment->id,
			$payment->stripe_customer,
			$payment->stripe_event,
			$payment->stripe_charge,
			$payment->stripe_card,

			Downloaders::prepareForCsv($user->getFullName()),
			$profile->id,
			Downloaders::prepareForCsv($box_title),
			$serie,
			readable_payment_type($payment->type),
			$payment->amount,
			readable_payment_status($payment->paid),
			$payment->last4,
			$payment->created_at->toDateTimeString()

			];

	}

	return Downloaders::makeCsvFromArray($file_name, $output);

}

/**
 * Generate a CSV for the orders
 * @param  string $file_name the file name
 * @param  object $orders    the Order object
 * @return csv  
 */
function generate_csv_order($file_name, $orders, $short=false)
{

	if ($short) {

	// We make up the titles
		$output[0] = [

		'Utilisateur',
		'Téléphone utilisateur',
		'Email utilisateur',
		'Abonnement',
		'Destination / Spot',
		'Création'

		];

	} else {

		// We make up the titles
		$output[0] = [

		'ID',
		'Série',
		'Utilisateur',
		'Adresse utilisateur',
		'Téléphone utilisateur',
		'Email utilisateur',
		'Abonnement',
		'Questions',
		'Réponses',
		'Paiement',
		'A offrir',
		'Etat de la commande',
		'Mode',
		'Destination / Spot',
		'Création',
		'Statut de la commande'

		];

	}

	foreach ($orders as $order) {

		// We prepare some stuff
		$profile = $order->user_profile()->first();
		$user = $profile->user()->first();
		$box = $profile->box()->first();

		$email = $user->email;

		if ($box == NULL) $box_title = 'Non renseigné';
		else $box_title = $box->title;

		if ($box == NULL) $box_questions = 'Pas de question';
		else $box_questions = Downloaders::prepareForCsv(order_questions($box, $profile, " / "));

		if ($box == NULL) $box_answers = 'Pas de réponse';
		else $box_answers = Downloaders::prepareForCsv(order_answers($box, $profile, " / "));

		$paid = $order->already_paid." / ".$order->unity_and_fees_price;

		if ($order->gift) $order_gift == 'A offrir';
		else $order_gift = 'Pas à offrir';

		if ($order->locked) $order_locked = 'Commande bloquée';
		else $order_locked = 'Commande non bloquée';

		if ($order->take_away) $order_take_away = 'A emporter';
		else $order_take_away = 'En livraison';

		$order_spot_or_destination = Downloaders::prepareForCsv(order_spot_or_destination($order));
		$order_status = Downloaders::prepareForCsv(readable_order_status($order->status));

		if ($short) {

		$output[] = [

			Downloaders::prepareForCsv($user->getFullName()),
			Downloaders::prepareForCsv($user->phone),
			Downloaders::prepareForCsv($email),
			$box_title,
			$order_spot_or_destination,
			$order->created_at->toDateTimeString()

			];

		} else {

			$output[] = [

			$order->id, 
			$order->delivery_serie()->first()->delivery,
			Downloaders::prepareForCsv($user->getFullName()),
			Downloaders::prepareForCsv($user->getFullAddress()),
			Downloaders::prepareForCsv($user->phone),
			Downloaders::prepareForCsv($email),
			$box_title,
			$box_questions,
			$box_answers,
			$paid,
			$order_gift,
			$order_locked,
			$order_take_away,
			$order_spot_or_destination,
			$order->created_at->toDateTimeString(),
			$order_status


			];

		}

	}

	return Downloaders::makeCsvFromArray($file_name, $output);

}

/**
 * Make the folder if not already existing
 * @param  string $path to the folder
 * @return void     
 */
function make_folder($path) {

	if(!is_dir($path)) mkdir($path, 0777);

}

function generate_zip($name, $folder) {

	$zip_file = 'public/uploads/' . $name . '.zip';

	// We zip the folder itself
	$files = glob('public/uploads/' . $folder);
	Zipper::make($zip_file)->add($files);

	return Redirect::to($zip_file);

}

/**
 * Generate a PDF for the bills (linked to payments)
 */
function generate_pdf_bill($payment, $download=FALSE, $destination_folder=FALSE) {

	$user = $payment->user()->first();
	$profile = $payment->profile()->first();
	$user_order_preference = $profile->order_preference()->first();

	$order = $payment->order()->first();

	$box = $profile->box()->first();

	// In case the payment doesn't match any order in peculiar
	// So we will address to the user directly
	if ($order == NULL) {

		$billing = NULL;

	} else {

		$billing = $order->billing()->first();

	}

	View::share('user', $user);
	View::share('user_order_preference', $user_order_preference);
	View::share('box', $box);
	View::share('order', $order);
	View::share('billing', $billing);
	View::share('payment', $payment);
	View::share('profile', $profile);

	$html = View::make('pdf.bill');
	$pdf_name = $payment->bill_id;

	return generate_pdf($html, $pdf_name, $download, $destination_folder);

}

/**
 * Generate a PDF
 * @param  string $pdf_name           the pdf name at the end
 * @param   string the HTML view which will be used to generate the PDF
 * @param  boolean $download           will we download the pdf ? Or just show it in the browser ?
 * @param  string $destination_folder will we save it into the server ? If yes, here the destination folder
 * @return mixed 
 */
function generate_pdf($html, $pdf_name, $download, $destination_folder) {

	$pdf = new \Thujohn\Pdf\Pdf();

	if ($destination_folder) {

		$destinationPath = 'public/uploads/' . $destination_folder;
		make_folder($destinationPath);

		$outputName = $pdf_name;
		$pdfPath = $destinationPath . '/' . $outputName . '.pdf';

		File::put($pdfPath, $pdf->load($html, 'A4', 'portrait')->output());

	} else {

		if ($download) $pdf->load($html, 'A4', 'portrait')->download($pdf_name);
		else return $pdf->load($html, 'A4', 'portrait')->show();

	}

}

/**
 * Get the readable role of a user
 * @param  string $role
 * @return string
 */
function readable_role($role) {

	if ($role === 'admin') return 'Administrateur';
	elseif ($role === 'user') return 'Utilisateur';
	else return $role;

}

/**
 * Get the slug from the box question type
 * @param  string $slug e.g. tech-idea, tech-bug
 * @return string
 */
function readable_question_type($slug) {

	$arr = Config::get('bdxnbx.question_types');

	if (isset($arr[$slug])) return $arr[$slug];
	else return 'Inconnu';

}

/**
 * Check if the question type can have answers
 * @param  string  $type question type
 * @return boolean
 */
function has_no_answer_possible($type) {

	$arr_check = Config::get('bdxnbx.no_answer_question_type');
	
	if (in_array($type, $arr_check)) return TRUE;
	else return FALSE;

}


function convert_usd_to_cents($amount) {

	$raw_amount = str_replace('.', '', $amount);
	$converted_amount *= 100;

	return $converted_amount;

}
