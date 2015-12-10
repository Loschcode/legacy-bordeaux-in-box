<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPaymentProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_payment_profiles', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_profile_id')->unsigned()->nullable();

			// Fields
			$table->string('stripe_token');
			$table->string('stripe_card');
			$table->string('stripe_plan_id');
			$table->string('stripe_customer');

			// Indexes
			$table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');

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

		Schema::table('user_payment_profiles', function(Blueprint $table)
		{

			$table->dropForeign('user_payment_profiles_user_profile_id_foreign');

		});

		Schema::dropIfExists('user_payment_profiles');

	}

}
