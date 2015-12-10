<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserProfilesPriorityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_profiles', function($table)
		{

			// Soft deletes
			$table->enum('priority', ['high', 'medium', 'low']);

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

			// Soft deletes
			$table->dropColumn('priority');

		});


	}
}
