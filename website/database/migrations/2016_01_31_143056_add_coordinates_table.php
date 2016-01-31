<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    Schema::create('coordinates', function($table)
    {

      // Keys
      $table->increments('id');
      
      $table->string('place_id');

      $table->string('address');
      $table->string('zip');
      $table->string('city');
      $table->string('country');

      $table->float('latitude');
      $table->float('longitude');
      $table->text('formatted_address');
      
      // Indexes
      $table->index('place_id');
      $table->index('latitude');
      $table->index('longitude');

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

      Schema::dropIfExists('coordinates');

    }
}
