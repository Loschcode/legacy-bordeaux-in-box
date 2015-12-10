<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOrderPreferencesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_order_preferences', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_profile_id')->unsigned()->nullable();
			$table->integer('delivery_spot_id')->unsigned()->nullable();

			// Fields
			$table->integer('frequency');
			$table->string('stripe_plan');
			$table->float('unity_price');
			$table->float('delivery_fees');
			$table->boolean('take_away');
			$table->boolean('gift');

			// Indexes
			$table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
			$table->foreign('delivery_spot_id')->references('id')->on('delivery_spots')->onDelete('cascade');

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

		Schema::table('user_order_preferences', function(Blueprint $table)
		{

			$table->dropForeign('user_order_preferences_user_profile_id_foreign');
			$table->dropForeign('user_order_preferences_delivery_spot_id_foreign');

		});

		Schema::dropIfExists('user_order_preferences');

	}

}
