<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderPaymentsAndRemoveOrderIdFromPaymentsWithLoop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    Schema::create('order_payments', function($table)
    {

      // Keys
      $table->increments('id');

      // Fields
      $table->integer('payment_id')->unsigned();
      $table->integer('order_id')->unsigned();

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

    Schema::dropIfExists('order_payments');

    }
}
