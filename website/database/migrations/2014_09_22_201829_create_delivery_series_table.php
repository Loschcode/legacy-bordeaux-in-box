<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliverySeriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('delivery_series', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->date('delivery');
			$table->datetime('closed')->nullable();
			$table->integer('counter')->nullable();

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

		Schema::dropIfExists('delivery_series');

	}


}
