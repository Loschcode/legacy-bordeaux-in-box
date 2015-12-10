<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserEmailsFullyAuthorizedTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{

			$table->datetime('emails_fully_authorized')->nullable();

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('users', function(Blueprint $table)
		{

			// Soft deletes
			$table->dropColumn('emails_fully_authorized');

		});


	}

}
