<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverySpotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_spots', function($table)
		{

			// Keys
			$table->increments('id');

			$table->string('name');
			$table->string('city');
			$table->string('zip');
			$table->text('address');

			$table->boolean('active');

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

		Schema::dropIfExists('delivery_spots');

	}

}
