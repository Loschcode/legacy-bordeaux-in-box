<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use App\Models\CustomerPaymentProfile;
use App\Models\Payment;
use App\Models\Order;

use App\Libraries\Payments;

/**
 * Generate the admins
 */
class OrderNormalizeUnityAndFeesPrice extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'order:normalize-unity-and-fees-price';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Normalize the unity price / fees price from the order preferences of the old school system';

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
    $orders = Order::where('unity_price', '=', 0)->where('delivery_fees', '=', 0)->get();

    foreach ($orders as $order) {

      $this->info('We normalize the order `'.$order->id.'`');

      $order_preference = $order->customer_preference()->first();

      $order->delivery_fees = $order_preference->delivery_fees;
      $order->unity_price = $order_preference->unity_price;

      $this->info('The order has been updated : '.$order->unity_price. ' / '. $order->delivery_fees);

      $order->save();

    }

    $this->line('End of process.');

  }

}
