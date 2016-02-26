<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerConnectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      Schema::create('customer_connects', function($table)
      {


        // Keys
        $table->increments('id');
        $table->text('token');
        $table->integer('customer_id')->unsigned()->nullable();

        // Indexes
        $table->index('customer_id');

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

      Schema::dropIfExists('customer_connects');

    }
}
