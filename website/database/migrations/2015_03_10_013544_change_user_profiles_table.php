<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserProfilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_profiles', function($table)
		{

			// Keys
			$table->enum('status', array('not-subscribed', 'in-progress', 'subscribed', 'expired'));

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

			// Columns to remove
			$table->dropColumn('status');

		});


	}

}
