<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDeliveryPricesLabelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('delivery_prices', function($table)
		{

			// Keys
			$table->text('title');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('delivery_prices', function($table)
		{

			// Columns to remove
			$table->dropColumn('title');

		});

	}

}
