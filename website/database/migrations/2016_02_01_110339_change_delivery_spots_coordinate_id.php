<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\DeliverySpot;

class ChangeDeliverySpotsCoordinateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('delivery_spots', function($table)
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
      $delivery_spots = DeliverySpot::get();
      foreach ($delivery_spots as $delivery_spot) {
        $delivery_spot->coordinate_id = Coordinate::getMatchingOrGenerate($delivery_spot->address, $delivery_spot->zip, $delivery_spot->city)->id;
        $delivery_spot->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('delivery_spots', function($table)
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

      Schema::table('delivery_spots', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('coordinate_id');

        $table->string('address');
        $table->string('zip');
        $table->string('city');
        
      });

    }
}
