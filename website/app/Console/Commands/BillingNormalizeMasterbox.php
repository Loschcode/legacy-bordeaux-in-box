<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\CustomerPaymentProfile;
use App\Models\Payment;
use App\Models\Order;
use App\Models\CompanyBilling;
use App\Models\CompanyBillingLine;

use App\Libraries\Payments;

use Crypt;

/**
 * Generate the admins
 */
class BillingNormalizeMasterbox extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'billing:normalize-masterbox';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the billing system and fill all the billing / billing lines from old school orders';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {

    $this->line('We will normalize the bills ... ');

    /**
     * Let's do it
     */
    if ($value = $this->option('clean')) {

      $company_billings = CompanyBilling::get();
      $company_billing_lines = CompanyBillingLine::get();

      foreach ($company_billings as $company_billing) {
        $company_billing->delete();
      }

      $orders = Order::get();
      foreach ($orders as $order) {
        $order->company_billing_id = NULL;
        $order->save();
      }

      $this->warn('We cleaned the company billings / company billing lines');

    }

    $orders = Order::whereNull('company_billing_id')->get();

    $this->line('We will first take it from the orders ...');

    foreach ($orders as $order) {

      /**
       * We prepare the data we will use
       */
      
      $this->info('We will process the order `'.$order->id.'`');
      
      $company_billing = generate_new_company_billing_from_order($order, TRUE);

      $payments = $order->payments()->get();

      foreach ($payments as $payment) {

        $this->info('A payment was found for this entry');

        $order = $payment->orders()->first();

        if ($order !== NULL) {
          $unity_price = $order->unity_price;
          $delivery_fees = $order->delivery_fees;
        }

        if ($payment->amount >= 0) {

          if ($order !== NULL) {

            $billing_line = new CompanyBillingLine;
            $billing_line->company_billing_id = $company_billing->id;
            $billing_line->payment_id = $payment->id;
            $billing_line->label = "Achat de la box surprise";
            $billing_line->amount = $unity_price;
            $billing_line->save();

            $billing_line = new CompanyBillingLine;
            $billing_line->company_billing_id = $company_billing->id;
            $billing_line->payment_id = $payment->id;
            $billing_line->label = "Frais de transport de la box surprise";
            $billing_line->amount = $delivery_fees;
            $billing_line->save();

          } else {

            $billing_line = new CompanyBillingLine;
            $billing_line->company_billing_id = $company_billing->id;
            $billing_line->payment_id = $payment->id;
            $billing_line->label = "Achat et frais de transport de la box surprise";
            $billing_line->amount = $payment->amount;
            $billing_line->save();

          }

        } else {

          $billing_line = new CompanyBillingLine;
          $billing_line->company_billing_id = $company_billing->id;
          $billing_line->payment_id = $payment->id;
          $billing_line->label = "Remboursement de la box surprise";
          $billing_line->amount = $payment->amount;
          $billing_line->save();

        }

      }

      $this->info('We save the changes for this entry.');
      $company_billing->save();

      $order->company_billing_id = $company_billing->id;
      $order->save();

    }

    $this->info('We will create billing for orphan payments ...');

    $payments = Payment::withoutOrders()->get();

    foreach ($payments as $payment) {

      $this->info('We will process the payment `'.$payment->id.'`');

      $company_billing = generate_new_company_billing_without_order($payment);

      if ($payment->amount >= 0) {

        $billing_line = new CompanyBillingLine;
        $billing_line->company_billing_id = $company_billing->id;
        $billing_line->payment_id = $payment->id;
        $billing_line->label = "Achat et frais de transport de la box surprise";
        $billing_line->amount = $payment->amount;
        $billing_line->save();

      } else {

        $billing_line = new CompanyBillingLine;
        $billing_line->company_billing_id = $company_billing->id;
        $billing_line->payment_id = $payment->id;
        $billing_line->label = "Remboursement de la box surprise";
        $billing_line->amount = $payment->amount;
        $billing_line->save();

      }

      $this->info('We save the changes for this entry.');


    }

    $this->line('End of process.');

  }

  /**
    * Get the console command arguments.
    *
    * @return array
    */
    protected function getOptions()
    {

        return array(
            array('clean', null, null, 'We will first clean the billing tables'),
        );

    }

}