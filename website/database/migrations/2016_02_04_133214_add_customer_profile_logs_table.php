<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerProfileLogsTable extends Migration
{

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('customer_profile_logs', function($table)
    {

      // Keys
      $table->increments('id');
      $table->integer('administrator_id')->unsigned()->nullable();
      $table->integer('customer_profile_id')->unsigned()->nullable();


      // Fields
      $table->text('log');
      $table->text('metadata');

      // Indexes
      $table->index('administrator_id');
      $table->index('customer_profile_id');

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

    Schema::dropIfExists('customer_profile_logs');

  }

}
