<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrdersUnityPriceFeesPriceTable extends Migration
{


  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('orders', function($table)
    {

      $table->float('unity_price');
      $table->float('delivery_fees');

    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    Schema::table('orders', function(Blueprint $table)
    {

     $table->dropColumn('unity_price');
     $table->dropColumn('delivery_fees');

    });

  }


}
