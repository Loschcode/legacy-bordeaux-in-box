<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserProfileNotesTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profile_notes', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();
			

			// Fields
			$table->text('note');

			// Indexes
			$table->index('user_id');
			$table->index('user_profile_id');

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

		Schema::table('user_profile_notes', function(Blueprint $table)
		{

		});

		Schema::dropIfExists('user_profile_notes');

	}
}
