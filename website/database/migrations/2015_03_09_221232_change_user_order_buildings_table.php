<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserOrderBuildingsTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_order_buildings', function($table)
		{

			// Keys
			//$table->integer('delivery_serie_id')->unsigned()->nullable();

			// Indexes
			//$table->index('delivery_serie_id');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('user_order_buildings', function(Blueprint $table)
		{

			// Columns to remove
			//$table->dropColumn('delivery_serie_id');

		});


		//Schema::dropIfExists('box_questions');

	}

}
