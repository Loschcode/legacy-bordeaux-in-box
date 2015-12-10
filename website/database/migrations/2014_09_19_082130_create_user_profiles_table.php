<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profiles', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('box_id')->unsigned()->nullable();

			$table->string('stripe_customer');

			$table->string('contract_id');

			// Indexes
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');

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

		Schema::table('user_profiles', function(Blueprint $table)
		{

			$table->dropForeign('user_profiles_user_id_foreign');
			$table->dropForeign('user_profiles_box_id_foreign');

		});

		Schema::dropIfExists('user_profiles');

	}


}
