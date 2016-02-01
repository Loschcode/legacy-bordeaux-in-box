<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Coordinate;
use App\Models\CustomerOrderBuilding;

class ChangeCustomerOrderBuildingDestinationCoordinateId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::table('customer_order_buildings', function($table)
      {
        $table->integer('destination_coordinate_id');
        $table->index('destination_coordinate_id');
      });

      /**
       * We also convert it
       */
      
      /**
       * WARNING :
       * If this blow up, comment the getAddressAttribute accessors and equivalents
       */
      $customer_order_buildings = CustomerOrderBuilding::get();
      foreach ($customer_order_buildings as $customer_order_building) {
        $customer_order_building->destination_coordinate_id = Coordinate::getMatchingOrGenerate($customer_order_building->destination_address, $customer_order_building->destination_zip, $customer_order_building->destination_city)->id;
        $customer_order_building->save();
      }

      /**
       * Then we remove the column
       */
      Schema::table('customer_order_buildings', function($table)
      {
        $table->dropColumn('destination_address');
        $table->dropColumn('destination_zip');
        $table->dropColumn('destination_city');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customer_order_buildings', function($table)
      {
        // WE CANNOT REALLY ROLLBACK BUT I PUT IT HERE ANYWAY BY PRINCIPLE
        $table->dropColumn('destination_coordinate_id');

        $table->string('destination_address');
        $table->string('destination_zip');
        $table->string('destination_city');
        
      });

    }
}
