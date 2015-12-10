<?php

/**
 * Payment system
 * by Laurent Schaffner
 */
class Payments {

    /**
     * Make a new customer and return its id
     * @param  string $stripe_card   card stripe
     * @param  object $user    user object
     * @param  object $profile profile object
     * @return mixed          stripe customer id
     */
    public static function makeCustomer($stripe_card, $user, $profile)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        try {

        $callback = Stripe_Customer::create(array(

            "description" => "Utilisateur ID `" . $user->id . '`',
            "card" => $stripe_card,

            "email" => $user->email,

            "metadata" => [

                'user_id' => $user->id,
                'user_profile_id' => $profile->id,
                'payment_type' => FALSE

            ]

            ));

        } catch(Stripe_CardError $e) {

            return ['Votre carte a été déclinée'];

        } catch (Stripe_InvalidRequestError $e) {

            return ['Les informations de paiements sont invalides'];
        
        } catch (Stripe_AuthenticationError $e) {

            return ['La connexion avec le serveur de paiement est impossible'];

        } catch (Stripe_ApiConnectionError $e) {

            return ['La connexion avec le service de gestion des paiement est impossible'];

        } catch (Stripe_Error $e) {

            return ['Une erreur s\'est produite lors de la tentative de paiement'];
        
        } catch (Exception $e) {

            return ['Une erreur s\'est produite lors de l\'accès au service de paiement'];
        
        }

        if (isset($callback->id)) return $callback->id;
        return [];

    }

    /**
     * Add a card to a customer
     * @param string $stripe_customer stripe customer id
     * @param string $stripe_token    stripe token (or card id)
     */
    public static function removeCard($stripe_customer, $stripe_card)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        try {

        $customer = Stripe_Customer::retrieve($stripe_customer);
        $callback = $customer->cards->retrieve($stripe_card)->delete();

        } catch (Exception $e) {

          if (isset($callback->deleted)) return $callback->id;
          return $e;

        }

    }

    /**
     * Add a card to a customer
     * @param string $stripe_customer stripe customer id
     * @param string $stripe_token    stripe token (or card id)
     */
    public static function addCard($stripe_customer, $stripe_token)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        try {

          $cu = Stripe_Customer::retrieve($stripe_customer);
          $callback = $cu->cards->create(array("card" => $stripe_token));

        } catch(Stripe_CardError $e) {

            return ['Votre carte a été déclinée'];

        } catch (Stripe_InvalidRequestError $e) {

            return ['Les informations de la carte sont invalides'];
        
        } catch (Stripe_AuthenticationError $e) {

            return ['La connexion avec le serveur sécurisé est impossible'];

        } catch (Stripe_ApiConnectionError $e) {

            return ['La connexion avec le service de gestion des cartes est impossible'];

        } catch (Stripe_Error $e) {

            return ['Une erreur s\'est produite lors de la tentative d\'ajout de carte bancaire'];
        
        } catch (Exception $e) {

            return ['Une erreur s\'est produite lors de l\'accès au service de cartes bancaires'];
        
        }

        if (isset($callback->id)) return $callback->id;
        else return FALSE;

    }

    public static function retrieveStripePlan($plan_name)
    {

      $api_key = Config::get('stripe.api_key');
      Stripe::setApiKey($api_key);

      try {

        $feedback = Stripe_Plan::retrieve($plan_name);

      } catch (Exception $e) {

        return [

        'success' => false,
        'error' => $e

        ];
        
      }

      return [

      'success' => true,
      'id' => $feedback->id

      ];

    }

    public static function makeStripePlan($plan_name, $plan_price, $frequency)
    {

      $api_key = Config::get('stripe.api_key');
      Stripe::setApiKey($api_key);

      $amount_in_cents = ($plan_price * 100);

      try {

        $feedback = Stripe_Plan::create([

          'amount' => $amount_in_cents,
          'interval' => $frequency,
          'name' => 'Plan ' . $plan_price,
          'currency' => 'eur',
          'id' => $plan_name

          ]);

      } catch (Exception $e) {

        return [

        'success' => false,
        'error' => $e

        ];

      }

      return [

      'success' => true,
      'id' => $feedback->id

      ];

    }

    public static function makeOrRetrieveStripePlan($plan_name, $plan_price)
    {

      $api_key = Config::get('stripe.api_key');
      Stripe::setApiKey($api_key);

      $try_retrieve = self::retrieveStripePlan($plan_name);

      if ($try_retrieve['success'] === false) {

        return self::makeStripePlan($plan_name, $plan_price, 'month');

      } else {

        return $try_retrieve;

      }

    }

    /**
     * Make a subscription with a defined plan
     * @param  string $stripe_customer stripe customer id
     * @param  object $user            user db object
     * @param  object $profile         user profile db object
     * @param  string $plan            named plan
     * @return mixed                  bool / id
     */
    public static function makeSubscription($stripe_customer, $user, $profile, $plan_name, $plan_price)
    {
        
        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        $stripe_customer = Stripe_Customer::retrieve($stripe_customer);
        $stripe_plan = self::makeOrRetrieveStripePlan($plan_name, $plan_price);

        try {

            $callback = $stripe_customer->subscriptions->create(array(

                "plan" => $stripe_plan['id'],

                "metadata" => [

                    'user_id' => $user->id,
                    'user_profile_id' => $profile->id,
                    'payment_type' => 'plan'

                ]

                ));


        } catch(Stripe_CardError $e) {

            return ['Votre carte a été déclinée'];

        } catch (Stripe_InvalidRequestError $e) {

            return ['Les informations de paiements sont invalides'];
        
        } catch (Stripe_AuthenticationError $e) {

            return ['La connexion avec le serveur de paiement est impossible'];

        } catch (Stripe_ApiConnectionError $e) {

            return ['La connexion avec le service de gestion des paiement est impossible'];

        } catch (Stripe_Error $e) {

            return ['Une erreur s\'est produite lors de la tentative de paiement'];
        
        } catch (Exception $e) {

            return ['Une erreur s\'est produite lors de l\'accès au service de paiement'];
        
        }

        if (isset($callback->id)) return $callback->id;
        else return [];

    }

    public static function retrieveLastCard($stripe_customer)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        $cu = Stripe_Customer::retrieve($stripe_customer);
        $cards = $cu->cards;

        $last_card = $cards->data[count($cards->data)-1];

        return $last_card->id;


    }

    /**
     * Get the last4 of a card from a specific customer
     * @param  string $stripe_customer_id stripe customer id
     * @param  string $stripe_card_id the card itself
     * @return string
     */
    public static function getLast4FromCard($stripe_customer_id, $stripe_card_id) {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        // We get the customer
        try {

        $cu = Stripe_Customer::retrieve($stripe_customer_id);
        $cards = $cu->cards;

        $last_card = $cards->data[count($cards->data)-1];

        } catch (Exception $e) {

          return FALSE;

        }

        if (isset($last_card->last4)) return $last_card->last4;
        return FALSE;

    }

    /**
     * Get a plan from a subscription id
     * @param  string $stripe_subscription stripe subscription id
     * @return mixed                  object / false
     */
    public static function getPlanFromSubscription($stripe_customer_id, $stripe_subscription_id)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        // We get the customer
        try {
        
        $stripe_customer = Stripe_Customer::retrieve($stripe_customer_id);

        } catch (Exception $e) {

          return FALSE;

        }

        // We get the subscription
        try {
        
        $stripe_subscription = $stripe_customer->subscriptions->retrieve($stripe_subscription_id);

        } catch (Exception $e) {

          return FALSE;

        }

        if (isset($stripe_subscription->plan)) return $stripe_subscription->plan;
        return FALSE;

    }

    /**
     * Get a customer
     * @param  string $stripe_subscription stripe subscription id
     * @return mixed                  object / false
     */
    public static function getCustomer($stripe_customer)
    {

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);
        
        $cu = Stripe_Customer::retrieve($stripe_customer);
        return $cu;

    }

    /**
     * Cancel a subscription
     * @param  string $stripe_customer strip customer id
     * @param  string $stripe_plan     stripe plan id (that we got when we subscribed)
     * @return mixed                  id / false
     */
    public static function cancelSubscription($stripe_customer, $stripe_subscription, $no_recursive=FALSE)
    {

        // 
        // THIS SHOULD BE IMPROVED : we should set $stripe_subscription as optional
        // if there's none it just try to cancel the first subscription
        // -> All the process is already written below, just a few changes
        // Laurent, 9th October 2015
        // 

        $api_key = Config::get('stripe.api_key');
        Stripe::setApiKey($api_key);

        // We get the customer, then subscription and we cancel
        try {
          
          $cu = Stripe_Customer::retrieve($stripe_customer);

          // Get the subscriptions listing (in case the $stripe_subscription doesn't exist)
          $datas_subscriptions = $cu->subscriptions->all();
          if (isset($datas_subscriptions->data[0])) $first_subscription = $datas_subscriptions->data[0]->id;
          else $first_subscription = NULL;

          // End of subscriptions fetch

          $callback = $cu->subscriptions->retrieve($stripe_subscription)->cancel();

        } catch (Exception $e) {

          Log::info("Cancel Subscription Exception $e");

          // In case the problem occur BEFORE we try to get the subscription (customer unknown)
          if (isset($first_subscription)) {

            if (($first_subscription !== NULL) && ($no_recursive !== TRUE)) {

              Log::info("We will try to cancel the first subscription found (`$first_subscription`)");
              return self::cancelSubscription($stripe_customer, $first_subscription, TRUE);

            }

          }

          return FALSE;

        }

        if (isset($callback->id)) return $callback->id;
        return FALSE;

    }

    /**
     * We invoice a customer once
     * @param  string $stripe_customer customer stripe
     * @param  object $user            db user
     * @param  object $profile         db user profile
     * @param  float $raw_amount      amount (e.g. 50.00)
     * @return mixed                  error string / true
     */
    public static function invoice($stripe_customer, $user, $profile, $raw_amount)
    {

        $api_key = Config::get('stripe.api_key');
        $amount = $raw_amount * 100;

        Stripe::setApiKey($api_key);

        try {

            $charge = Stripe_Charge::create(array(

              "amount" => $amount, 
              "currency" => "eur",
              "description" => 'Paiement utilisateur ID `' . $user->id . '` (PROFILE ID `'.$profile->id.'` / ABONNEMENT `'.$profile->contract_id.'`)',

              "customer" => $stripe_customer,

              "metadata" => [

                'user_id' => $user->id,
                'user_profile_id' => $profile->id,
                'payment_type' => 'direct_invoice'

              ]

            ));

        } catch(Stripe_CardError $e) {

            return ['Votre carte a été déclinée'];

        } catch (Stripe_InvalidRequestError $e) {

            return ['Les informations de paiements sont invalides'];
        
        } catch (Stripe_AuthenticationError $e) {

            return ['La connexion avec le serveur de paiement est impossible'];

        } catch (Stripe_ApiConnectionError $e) {

            return ['La connexion avec le service de gestion des paiement est impossible'];

        } catch (Stripe_Error $e) {

            return ['Une erreur s\'est produite lors de la tentative de paiement'];
        
        } catch (Exception $e) {

            return ['Une erreur s\'est produite lors de l\'accès au service de paiement'];
        
        }

        return TRUE;

    }

}