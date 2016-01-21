<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingLinesTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('company_billing_lines', function($table)
    {

      // Keys
      $table->increments('id');

      // Fields
      $table->string('payment_id');
      $table->text('label');
      $table->float('amount');
      
      $table->integer('company_billing_id')->unsigned();

      // Timestamps
      $table->timestamps();

    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {

    Schema::dropIfExists('company_billing_lines');

  }

}
