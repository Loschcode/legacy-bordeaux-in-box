<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Payment;
use App\Models\OrderPayment;

class RemoveOrderIdFromPaymentsAndLoopIt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      $payments = Payment::whereNotNull('order_id')->get();

      foreach ($payments as $payment) {

        $order_payment = new OrderPayment;
        $order_payment->payment_id = $payment->id;
        $order_payment->order_id = $payment->order_id;
        $order_payment->save();

      }

      Schema::table('payments', function($table)
      {
         $table->dropForeign('payments_order_id_foreign');
         $table->dropColumn('order_id');
      });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // TO DO IF WE ROLLBACK SOMEDAY
    }
}
