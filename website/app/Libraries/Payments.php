<?php 

namespace App\Libraries;

use Stripe\Stripe, Config, Log, \Exception;

/**
 * Payment system
 * by Laurent Schaffner
 */
class Payments {

    public static function prepare_stripe()
    {

      $api_key = Config::get('services.stripe.secret');
      \Stripe\Stripe::setApiKey($api_key);
      \Stripe\Stripe::setApiVersion(Config::get('services.stripe.version'));

    }

    /**
     * Make a new customer and return its id
     * @param  string $stripe_card   card stripe
     * @param  object $customer    user object
     * @param  object $profile profile object
     * @return mixed          stripe customer id
     */
    public static function makeCustomer($stripe_card, $customer, $profile, $description='', $contract_id=NULL)
    {

      self::prepare_stripe();

        try {

        $callback = \Stripe\Customer::create(array(

            "description" => $description,
            "card" => $stripe_card,

            "email" => $customer->email,

            "metadata" => [

                'customer_id' => $customer->id,
                'customer_profile_id' => $profile->id,
                'contract_id' => $contract_id,

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

      self::prepare_stripe();

        try {

        $customer = \Stripe\Customer::retrieve($stripe_customer);
        $callback = $customer->sources->retrieve($stripe_card)->delete();

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

      self::prepare_stripe();

        try {

          $cu = \Stripe\Customer::retrieve($stripe_customer);
          $callback = $cu->sources->create(array("card" => $stripe_token));

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

      $api_key = Config::get('services.stripe.secret');
      \Stripe\Stripe::setApiKey($api_key);

      try {

        $feedback = \Stripe\Plan::retrieve($plan_name);

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

      self::prepare_stripe();

      $amount_in_cents = ($plan_price * 100);

      try {

        $feedback = \Stripe\Plan::create([

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

      self::prepare_stripe();

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
     * @param  object $customer            user db object
     * @param  object $profile         user profile db object
     * @param  string $plan            named plan
     * @return mixed                  bool / id
     */
    public static function makeSubscription($stripe_customer, $customer, $profile, $plan_name, $plan_price)
    {
        
        self::prepare_stripe();

        $stripe_customer = \Stripe\Customer::retrieve($stripe_customer);
        $stripe_plan = self::makeOrRetrieveStripePlan($plan_name, $plan_price);

        try {

            $callback = $stripe_customer->subscriptions->create(array(

                "plan" => $stripe_plan['id'],

                "metadata" => [

                    'customer_id' => $customer->id,
                    'customer_profile_id' => $profile->id,
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

        self::prepare_stripe();

        $cu = \Stripe\Customer::retrieve($stripe_customer);
        $sources = $cu->sources;

        $last_card = $sources->data[count($sources->data)-1];

        return $last_card->id;


    }

    /**
     * Get the last4 of a card from a specific customer
     * @param  string $stripe_customer_id stripe customer id
     * @param  string $stripe_card_id the card itself
     * @return string
     */
    public static function getLast4FromCard($stripe_customer_id, $stripe_card_id) {

        self::prepare_stripe();

        // We get the customer
        try {

        $cu = \Stripe\Customer::retrieve($stripe_customer_id);
        $sources = $cu->sources;

        $last_card = $sources->data[count($sources->data)-1];

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

        self::prepare_stripe();

        // We get the customer
        try {
        
        $stripe_customer = \Stripe\Customer::retrieve($stripe_customer_id);

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

        self::prepare_stripe();

        // We get the customer, then subscription and we cancel
        try {
          
          $cu = \Stripe\Customer::retrieve($stripe_customer);

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
     * @param  object $customer            db user
     * @param  object $profile         db user profile
     * @param  float $raw_amount      amount (e.g. 50.00)
     * @return mixed                  error string / true
     */
    public static function invoice($stripe_customer, $customer, $profile, $raw_amount)
    {

        self::prepare_stripe();

        $amount = $raw_amount * 100;

        try {

            $charge = \Stripe\Charge::create(array(

              "amount" => $amount, 
              "currency" => "eur",
              "description" => 'Paiement utilisateur ID `' . $customer->id . '` (PROFILE ID `'.$profile->id.'` / ABONNEMENT `'.$profile->contract_id.'`)',

              "customer" => $stripe_customer,

              "metadata" => [

                'customer_id' => $customer->id,
                'customer_profile_id' => $profile->id,
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

    /**
     * Get a customer
     * @param  string $stripe_customer stripe customer id
     * @return mixed                  object / false
     */
    public static function getCustomer($stripe_customer)
    {

      self::prepare_stripe();

      try {

        $customer = \Stripe\Customer::retrieve($stripe_customer);

      } catch(Exception $e) {

          return ['success' => false, 'error' => $e];

      }

      return ['success' => true, 'customer' => $customer];

    }

    /**
     * Get an invoice
     * @param  string $stripe_invoice stripe invoice id
     * @return mixed                  object / false
     */
    public static function getInvoice($stripe_invoice)
    {

      self::prepare_stripe();

      try {

        $invoice = \Stripe\Invoice::retrieve($stripe_invoice);

      } catch(Exception $e) {

          return ['success' => false, 'error' => $e];

      }

      return ['success' => true, 'invoice' => $invoice];

    }

    /**
     * Get a charge
     * @param  string $stripe_charge stripe charge id
     * @return mixed object / false
     */
    public static function getCharge($stripe_charge)
    {

      self::prepare_stripe();

      try {
        
        $charge = \Stripe\Charge::retrieve($stripe_charge);

      } catch(Exception $e) {

          return ['success' => false, 'error' => $e];

      }

      return ['success' => true, 'charge' => $charge];

    }

    /**
     * Get a charge
     * @param  string $stripe_charge stripe charge id
     * @return mixed object / false
     */
    public static function getBalanceFeesFromCharge($stripe_charge)
    {

      self::prepare_stripe();

      try {
        
        $charge = \Stripe\Charge::retrieve($stripe_charge);

        $stripe_balance_transaction = $charge->balance_transaction;
        $balance_transaction = \Stripe\BalanceTransaction::retrieve($stripe_balance_transaction);
        $fees = $balance_transaction->fee;

      } catch(Exception $e) {

          return ['success' => false, 'error' => $e];

      }

      /**
       * Cents to Euros conversion
       */
      $fees = ($fees / 100);
      return ['success' => true, 'charge' => $charge, 'balance_transaction' => $balance_transaction, 'fees' => $fees];

    }

    public static function getSubscriptionidFromChargeId($stripe_charge_id)
    {

      $charge_callback = self::getCharge($stripe_charge_id);

      if (!$charge_callback['success'])
        return NULL;

      $stripe_invoice_id = $charge_callback['charge']['invoice'];

      if ($stripe_invoice_id === NULL)
        return NULL;

      $invoice_callback = self::getInvoice($stripe_invoice_id);

      if (!$invoice_callback['success'])
        return NULL;

      $stripe_subscription_id = $invoice_callback['invoice']['subscription'];

      if ($invoice_callback['invoice']['subscription'] === NULL)
        return NULL;

      return $stripe_subscription_id;

    }

    /**
     * Get a subscription
     * @param  string $stripe_customer stripe customer id
     * @param  string $stripe_subscription stripe subscription id
     * @return mixed object / false
     */
    public static function getSubscription($stripe_customer, $stripe_subscription)
    {

      self::prepare_stripe();

      try {
        
        $customer = \Stripe\Customer::retrieve($stripe_customer);
        $subscription = $customer->subscriptions->retrieve($stripe_subscription);

      } catch(Exception $e) {

          return ['success' => false, 'error' => $e];

      }

      return ['success' => true, 'customer' => $customer, 'subscription' => $subscription];

    }

}