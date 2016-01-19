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
class StripeNormalizePaymentFees extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'stripe:normalize-payment-fees';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the payments fees within the database if needed';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will normalize the Stripe payments ... ');

    $payments = Payment::orderBy('created_at', 'desc')->get();

    foreach ($payments as $payment) {

      $stripe_charge_id = $payment->stripe_charge;
      $amount = $payment->amount;

      $this->info("Analysing the charge `$stripe_charge_id` for $amount €`");

      $callback = Payments::getBalanceFeesFromCharge($stripe_charge_id);

      if ($callback['success']) {

        $fees = $callback['fees'];

        /**
         * If it's not a refund
         */
        if ($amount >= 0) {

          $payment->fees = +$fees;
          $payment->save();

        } else {

          $payment->fees = -$fees;
          $payment->save();

        }

        $this->info("Application fee updated : ".$payment->fees." €");

      } else {

        $this->error("Stripe Charge not found : " . $stripe_charge_id);

      }

    }

    $this->line('End of process.');

  }

}