<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\OrderBilling;

class ChangeOrderBillingsCoordinateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('order_billings', function($table)
      {
        $table->integer('coordinate_id');
        $table->index('coordinate_id');
      });

      /**
       * We also convert it
       */
      
      /**
       * WARNING :
       * If this blow up, comment the getAddressAttribute accessors and equivalents
       */
      $order_billings = OrderBilling::get();
      foreach ($order_billings as $order_billing) {
        $order_billing->coordinate_id = Coordinate::getMatchingOrGenerate($order_billing->address, $order_billing->zip, $order_billing->city)->id;
        $order_billing->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('order_billings', function($table)
      {
        $table->dropColumn('address');
        $table->dropColumn('zip');
        $table->dropColumn('city');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('order_billings', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('coordinate_id');

        $table->string('address');
        $table->string('zip');
        $table->string('city');
        
      });

    }
}
