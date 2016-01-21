<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrderBuildingsBoxFormRemoval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $order_buildings = \App\Models\CustomerOrderBuilding::where('step', '=', 'box-form')->get();
        foreach ($order_buildings as $order_building) {
          $order_building->delete();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
