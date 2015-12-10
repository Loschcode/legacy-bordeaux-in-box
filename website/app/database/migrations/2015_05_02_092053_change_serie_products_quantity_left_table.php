<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSerieProductsQuantityLeftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('serie_products', function($table)
		{

			// Soft deletes
			$table->integer('quantity_left');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('serie_products', function(Blueprint $table)
		{

			// Soft deletes
			$table->dropColumn('quantity_left');

		});


	}

}
