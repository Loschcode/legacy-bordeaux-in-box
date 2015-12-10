<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDeliverySeriesGoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('delivery_series', function($table)
		{

			// Keys
			$table->dropColumn('counter');
			$table->integer('goal');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('delivery_series', function($table)
		{

			// Columns to remove
			$table->dropColumn('goal');
			$table->integer('counter');

		});

	}

}
