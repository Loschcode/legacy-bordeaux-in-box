<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSerieProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('serie_products', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('partner_product_id')->unsigned()->nullable();
			$table->integer('delivery_serie_id')->unsigned()->nullable();

			$table->boolean('ready');

			$table->float('cost_per_unity');
			$table->float('value_per_unity');

			$table->integer('quantity');

			// Indexes
			$table->index('partner_product_id');
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

		Schema::dropIfExists('serie_products');

	}


}
