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
class BillingRemoveUnpaid extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'billing:remove-unpaid';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Remove the unpaid billings from the lines';

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
    $payments = Payment::where('paid', '=', FALSE)->get();

    foreach ($payments as $payment) {

      $company_billing_lines = $payment->company_billing_lines()->get();

      $this->info('We focus on #'.$payment->id.' payment');

      foreach ($company_billing_lines as $company_billing_line) {

        $this->info('We delete #'.$company_billing_line->id.' company billing');
        $company_billing_line->delete();
      }

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