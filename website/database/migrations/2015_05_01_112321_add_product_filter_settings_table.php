<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductFilterSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_filter_settings', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('delivery_serie_id')->unsigned()->nullable();

			$table->integer('large_products');
			$table->integer('medium_products');
			$table->integer('small_products');

			$table->float('max_desired_cost');
			$table->float('max_desired_weight');

			$table->integer('high_priority_difference');
			$table->integer('low_priority_difference');

			// Indexes
			$table->index('delivery_serie_id');

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

		Schema::dropIfExists('product_filter_box_settings');

	}


}
