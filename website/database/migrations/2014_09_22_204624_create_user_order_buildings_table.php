<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOrderBuildingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_order_buildings', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('user_order_preference_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();
			$table->integer('delivery_serie_id')->unsigned()->nullable();

			// Fields
			$table->text('step');

			$table->string('destination_first_name');
			$table->string('destination_last_name');
			$table->string('destination_city');
			$table->string('destination_zip');
			$table->text('destination_address');

			// Indexes
			$table->index('user_id');
			$table->index('user_order_preference_id');
			$table->index('user_profile_id');
			$table->index('delivery_serie_id');

			// Timestamps
			$table->timestamps();
			$table->softDeletes();

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

			//$table->dropForeign('user_order_buildings_user_id_foreign');
			//$table->dropForeign('user_order_buildings_user_order_preference_id_foreign');
			//$table->dropForeign('user_order_buildings_user_profile_id_foreign');

		});

		Schema::dropIfExists('user_order_buildings');

	}


}
