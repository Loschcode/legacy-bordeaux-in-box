<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDeliverySpotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('delivery_spots', function($table)
		{

			// Keys
			$table->string('slug');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('delivery_spots', function($table)
		{

			// Columns to remove
			$table->dropColumn('slug');

		});

	}

}
