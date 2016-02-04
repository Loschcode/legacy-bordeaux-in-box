<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\CustomerPaymentProfile;
use App\Models\Payment;

use App\Libraries\Payments;

/**
 * Generate the admins
 */
class StripeNormalizePaymentSubscriptions extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'stripe:normalize-payment-subscriptions';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the payments stripe_subscription within the database if needed.';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will normalize the stripe payments subscriptions ... ');

    /**
     * Let's do it
     */
    $payments = Payment::where('stripe_subscription', '=', '')->orderBy('created_at', 'desc')->get();

    foreach ($payments as $payment) {

        $stripe_charge_id = $payment->stripe_charge;

        $this->info("Analysing the charge `$stripe_charge_id`");

        /**
         * There's a duplicate of this in the Payments library
         * For log purpose it has been duplicated and a little changed there
         */
        $charge_callback = Payments::getCharge($stripe_charge_id);

        if (!$charge_callback['success']) {
          $this->error('Impossible to retrieve the charge.`');
          continue;
        }

        $stripe_invoice_id = $charge_callback['charge']['invoice'];

        if ($stripe_invoice_id === NULL) {
          $this->info('No invoice linked to the charge : nothing to do here.');
          continue;
        }

        $invoice_callback = Payments::getInvoice($stripe_invoice_id);

        if (!$invoice_callback['success']) {
          $this->error('Impossible to retrieve the invoice `'.$stripe_invoice_id.'` from the charge.`');
          continue;
        }

        $stripe_subscription_id = $invoice_callback['invoice']['subscription'];

        if ($invoice_callback['invoice']['subscription'] === NULL) {
          $this->info('No subscription linked to the invoice : nothing to do here.');
          continue;
        }

        $payment->stripe_subscription = $stripe_subscription_id;
        $payment->save();

        $this->info("Payment successfully updated.");

    }

    $this->line('End of process.');

  }

}