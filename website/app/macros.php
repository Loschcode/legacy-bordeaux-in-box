<?php

HTML::macro('getReadableProfilePriority', function($priority)
{

	return readable_profile_priority($priority);

});

/**
 * If the value is empty we return N/A
 */
HTML::macro('getReadableEmpty', function($value, $empty='N/A')
{
	if (empty($value))
	{
		return $empty;
	}
	else
	{
		return $value;
	}
});

/**
 * We take the contact possible services
 */
HTML::macro('getContactServices', function()
{

	return Config::get('bdxnbx.contact_service');

});

/**
 * We take the contact possible services
 */
HTML::macro('hasNoAnswerPossible', function($type)
{

	$arr_check = Config::get('bdxnbx.no_answer_question_type');

	if (in_array($type, $arr_check)) return TRUE;
	else return FALSE;

});



/**
 * Say yes or no (true / false)
 */
HTML::macro('boolYesOrNo', function($bool)
{

	if ($bool) return 'Oui';
	else return 'Non';

});


/**
 * We output the questions and answers in HTML (for the admin dashboard orders reading)
 */
HTML::macro('getOrderQuestionsAndAnswers', function($box, $profile)
{

	return order_questions_and_answers($box, $profile);

});

/**
 * We output the questions and answers in HTML (for the admin dashboard orders reading)
 */
HTML::macro('getReadableProductSize', function($size)
{

	$product_sizes_list = Config::get('bdxnbx.product_sizes');
	return $product_sizes_list[$size];

});


/**
 * We get the order spot / destination
 */
HTML::macro('getOrderSpotOrDestination', function($order)
{

	return order_spot_or_destination($order);

});

/**
 * We get the order spot / destination
 */
HTML::macro('getOrderSpotOrDestinationZip', function($order)
{

	return order_spot_or_destination_zip($order);

});

/**
 * We get readable locked for orders
 */
HTML::macro('getReadableOrderLocked', function($bool)
{

	if ($bool) return 'Bloqué';
	else return 'Editable';

});

/**
 * We take the possible questions type while creating questions box
 */
HTML::macro('getPossibleQuestionTypes', function()
{

	return Config::get('bdxnbx.question_types');

});

/**
 * We get readable boolean status (active = true / false)
 */
HTML::macro('getReadableActive', function($active)
{

	if ($active) return 'Activé';
	else return 'Désactivé';

});

/**
 * Get a readable version of the service involved
 */
HTML::macro('getReadableContactService', function($slug)
{

	return readable_contact_service($slug);

});

/**
 * Get a readable order status
 */
HTML::macro('getReadableOrderStatus', function($status)
{

	return readable_order_status($status);

});

/**
 * Get a readable payment type
 */
HTML::macro('getReadablePaymentType', function($type)
{

	return readable_payment_type($type);

});

/**
 * Get a readable payment status
 */
HTML::macro('getReadablePaymentStatus', function($status)
{

	return readable_payment_status($status);

});

/**
 * Get a readable take away (yes or not)
 */
HTML::macro('getReadableTakeAway', function($take_away)
{

	if ($take_away) return 'A emporter';
	else return 'En livraison';

});


/**
 * Get a readable question type involved
 */
HTML::macro('getReadableQuestionType', function($slug)
{

	return readable_question_type($slug);

});

/**
 * Get a readable role for the users
 */
HTML::macro('getReadableRole', function($role)
{

	return readable_role($role);

});

/**
 * Get html class color from the box slug
 */
HTML::macro('getColorFromBoxSlug', function($slug)
{

	$arr_check = Config::get('bdxnbx.box_spyro_color');

	if (isset($arr_check[$slug])) return $arr_check[$slug];
	else return '';

});

/**
 * We will generate a link for the admin to access the user profile, from the email
 */
HTML::macro('generateAdminLinkFromUserEmail', function($email)
{

	$user = User::where('email', '=', $email)->first();
	if ($user === NULL) return 'N/A';

	return "<a href='/admin/users/focus/".$user->id."'>".$user->getFullName()."</a>";

});

/**
 * Get html class color from profile status
 */
HTML::macro('getColorFromProfileStatus', function($status)
{

	if ($status === 'subscribed') return 'spyro-btn-primary';
	elseif ($status === 'not-subscribed') return 'spyro-btn-default';
	elseif ($status === 'in-progress') return 'spyro-btn-success';
	elseif ($status == 'expired') return 'spyro-btn-danger';
	else return '';

});

/**
 * Get a readable profile status
 */
HTML::macro('getReadableProfileStatus', function($status)
{

	if ($status === 'subscribed') return 'Abonné';
	elseif ($status === 'not-subscribed') return 'Non abonné';
	elseif ($status === 'in-progress') return 'En création';
	elseif ($status == 'expired') return 'Expiré';
	else return $status;

});


/**
 * Get a readable month from a date
 */
HTML::macro('convertMonth', function($date)
{

	$timestamp = strtotime($date);
	$month = date('m', $timestamp);

	if ($month == '1') return 'Janvier';
	if ($month == '2') return 'Février';
	if ($month == '3') return 'Mars';
	if ($month == '4') return 'Avril';
	if ($month == '5') return 'Mai';
	if ($month == '6') return 'Juin';
	if ($month == '7') return 'Juillet';
	if ($month == '8') return 'Aout';
	if ($month == '9') return 'Septembre';
	if ($month == '10') return 'Octobre';
	if ($month == '11') return 'Novembre';
	if ($month == '12') return 'Décembre';

});

/**
 * We take the matching page from the pages table (with the `slug`)
 */
HTML::macro('page', function($slug)
{
	$page = Page::where('slug', $slug)->first();

	if ($page)
	{
    return $page->content;
	}

});

/**
 * Shortcut for diffHumans() from Date plugin (translation integrated)
 */
HTML::macro('diffHumans', function($date, $diff=0) {

	if ($diff != 0) {

		$date_object = date_create($date);
		$date_object = date_modify($date_object, '-'.$diff.' day');
		$date = date_format($date_object,'Y-m-d');

	}

	return ucfirst(Date::createFromTimeStamp(strtotime($date))->diffForHumans());

});

/**
 * Macro to display simple info designed
 */
HTML::macro('info', function($info) {

	return '<div class="spyro-alert spyro-alert-inverse"><p class="left"><i class="fa fa-info"></i></p><p class="right">' . $info . '</p><div class="clearfix"></div></div>';

});

/**
 * Macro to display simple info designed
 */
HTML::macro('getAge', function($dateBirthday) {

	// It's an european date
	$dateBirthday = str_replace('/', '-', $dateBirthday);

	$birthday = \Carbon\Carbon::parse($dateBirthday);
	$now = \Carbon\Carbon::now('Europe/Paris');

	return $now->diffInYears($birthday);

});

HTML::macro('isBirthday', function($dateBirthday) {

	return is_birthday($dateBirthday);

});

/**
 * Macro to display in the dashboard answers of the "quizz"
 */
HTML::macro('displayQuizz', function ($box, $profile, $spacer=" ", $long=false) {

	$questions = $box->questions()->get();
	$output = '<div class="well">';

	foreach ($questions as $question) {

		if ((($long) && (empty($question->short_question))) or (empty($question->short_question))) $final_question = $question->question;
		else $final_question = $question->short_question;

		$output .= '<strong>' . $final_question . '</strong><br/>';

		$answers = $profile->answers();
		$old_reply = $answers->where('box_question_id', $question->id);

		if ($question->type === "text") {

			if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

		} elseif ($question->type === "textarea") {

			if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

		} elseif ($question->type === "date") {

			if ($old_reply->first() != NULL) $output .= $old_reply->first()->answer; else $output .= 'N/A';

		} else {

			if ($question->answers()->first() == NULL) {

				$output .= 'N/A';

			}

			foreach ($old_reply->get() as $answer) {

				$output .= $answer->answer.$spacer;

			}

		}

		$output .= '<br /><br/>';

	}

	$output .= '</div>';
	return $output;

});
