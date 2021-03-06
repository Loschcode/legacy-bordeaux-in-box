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
class StripeNormalizePaymentRefundOrders extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'stripe:normalize-payment-refund-orders';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the payments refund orders within the database if needed. If there a refund which has no order, we try assign one';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will normalize the stripe payments without order ... ');

    /**
     * Let's do it
     */
    $payments = Payment::where('amount', '<', 0)->orderBy('created_at', 'desc')->get();

    foreach ($payments as $payment) {

      if ($payment->orders()->first() === NULL) {

        $stripe_charge_id = $payment->stripe_charge;
        $amount = $payment->amount;
        
        $this->info("Analysing the charge `$stripe_charge_id` for $amount €`");

        $original_payment = Payment::where('stripe_charge', '=', $stripe_charge_id)->orderBy('created_at', 'asc')->first();
            
        if (($original_payment !== NULL) && ($original_payment->orders()->first() !== NULL)) {

          $payment->orders()->attach($original_payment->orders()->first()->id);

          $this->info("Order `".$original_payment->orders()->first()->id."` linked to the Payment `".$payment->id."`");

        } else {

          $this->error('Original payment orders was not found : ' . $stripe_charge_id);

        }

      }

    }

    $this->line('End of process.');

  }

}