<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\OrderDestination;

class ChangeOrderDestinationsCoordinateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('order_destinations', function($table)
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
      $order_destinations = OrderDestination::get();
      foreach ($order_destinations as $order_destination) {
        $order_destination->coordinate_id = Coordinate::getMatchingOrGenerate($order_destination->address, $order_destination->zip, $order_destination->city)->id;
        $order_destination->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('order_destinations', function($table)
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

      Schema::table('order_destinations', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('coordinate_id');

        $table->string('address');
        $table->string('zip');
        $table->string('city');
        
      });

    }
}
