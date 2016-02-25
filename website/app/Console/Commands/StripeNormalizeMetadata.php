<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\CustomerPaymentProfile;

use App\Libraries\Payments;

/**
 * Generate the admins
 */
class StripeNormalizeMetadata extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'stripe:normalize-metadata';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the metadata format if needed';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will normalize the stripe metadata ... ');

    /**
     * The subscriptions metadata must be customer_id|customer_profile_id and that's it.
     */

    $this->line("Let's check all the Stripe subscriptions metadata ...");

    $customer_payment_profiles = CustomerPaymentProfile::where('stripe_subscription', '!=', '')->orderBy('created_at', 'desc')->get();

    foreach ($customer_payment_profiles as $customer_payment_profile) {

      $stripe_customer_id = $customer_payment_profile->stripe_customer;
      $stripe_subscription_id = $customer_payment_profile->stripe_subscription;

      $callback = Payments::getSubscription($stripe_customer_id, $stripe_subscription_id);

      if ($callback['success']) {

        $stripe_subscription = $callback['subscription'];

        /**
         * Default masterbox branch if unset
         */
        if (!isset($stripe_subscription->metadata->branch)) {

          $stripe_subscription->metadata->branch = 'masterbox';
          $stripe_subscription->save();

          continue;

        }

        /**
         * If old user metadata, convert to customer
         */
        if ((isset($stripe_subscription->metadata->user_id)) && (isset($stripe_subscription->metadata->user_profile_id))) {

          $this->info("Old metadata detected, we will change them ... (".$stripe_subscription_id.")");

          $customer_id = $stripe_subscription->metadata->user_id;
          $customer_profile_id = $stripe_subscription->metadata->user_profile_id;

          $stripe_subscription->metadata->customer_id = $customer_id;
          $stripe_subscription->metadata->customer_profile_id = $customer_profile_id;
          $stripe_subscription->metadata->branch = 'masterbox';

          $stripe_subscription->metadata->user_id = NULL;
          $stripe_subscription->metadata->user_profile_id = NULL;

          $stripe_subscription->save();

          continue;

        }

        $this->info("Metadata seem already well done. Nothing to do here.");



      } else {

        $this->error("Stripe Subscription not found : " . $stripe_subscription_id);

      }

    }

    /**
     * The customer metadata must be customer_id|customer_profile_id and that's it.
     */

    $this->line("Let's check all the Stripe customers metadata ...");

    $customer_payment_profiles = CustomerPaymentProfile::orderBy('created_at', 'desc')->get();

    foreach ($customer_payment_profiles as $customer_payment_profile) {

      $stripe_customer_id = $customer_payment_profile->stripe_customer;
      $callback = Payments::getCustomer($stripe_customer_id);

      if ($callback['success']) {

        $stripe_customer = $callback['customer'];

        if ((isset($stripe_customer->metadata->user_id)) && (isset($stripe_customer->metadata->user_profile_id))) {

          $this->info("Old metadata detected, we will change them ... (".$stripe_customer_id.")");

          $customer_id = $stripe_customer->metadata->user_id;
          $customer_profile_id = $stripe_customer->metadata->user_profile_id;

          $stripe_customer->metadata->customer_id = $customer_id;
          $stripe_customer->metadata->customer_profile_id = $customer_profile_id;
          $stripe_customer->metadata->contract_id = $customer_payment_profile->profile()->first()->contract_id;
          $stripe_customer->metadata->branch = 'masterbox';

          $stripe_customer->metadata->user_id = NULL;
          $stripe_customer->metadata->user_profile_id = NULL;
          $stripe_customer->metadata->payment_type = NULL;

          $stripe_customer->save();

          //Payments::updateCustomerMetadata($stripe_customer, $new_metadata);

        } else {

          $this->info("Metadata seem already well done. Nothing to do here.");

        }


      } else {

        $this->error("Stripe Customer not found : " . $stripe_customer_id);

      }

    }

    $this->line('End of process.');

  }

}