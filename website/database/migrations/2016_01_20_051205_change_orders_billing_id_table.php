<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeOrdersBillingIdTable extends Migration
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

      $table->integer('company_billing_id')->index()->nullable();

    });

    Schema::table('payments', function($table) {

      $table->dropColumn('bill_id');
  
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

     $table->dropColumn('company_billing_id');

    });

    Schema::table('payments', function($table) {

      $table->string('bill_id');
  
    });
  }

}
