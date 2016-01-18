<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCustomerOrderBuildingsPaidAtTable extends Migration
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
        $table->datetime('paid_at')->nullable();
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
        $table->dropColumn('paid_at');
      });

    }
}
