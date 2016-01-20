<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('company_billings', function($table)
    {

      // Keys
      $table->increments('id');

      // Fields

      $table->string('branch');
      $table->string('customer_id');
      $table->string('contract_id');
      $table->string('bill_id');
      $table->text('encrypted_access');
      $table->string('title');
      $table->string('first_name');
      $table->string('last_name');
      $table->string('city');
      $table->text('address');
      $table->string('zip');

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

    Schema::dropIfExists('company_billings');

  }

}
