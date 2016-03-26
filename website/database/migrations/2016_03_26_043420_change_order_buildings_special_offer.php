<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrderBuildingsSpecialOffer extends Migration
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

        $table->integer('end_series_special_offer')->nullable();

        $table->index('end_series_special_offer');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

      Schema::table('customer_order_buildings', function($table) {

        $table->dropColumn('end_series_special_offer');
    
      });

    }
}
