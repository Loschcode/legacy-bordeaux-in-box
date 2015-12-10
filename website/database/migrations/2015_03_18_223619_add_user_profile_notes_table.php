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
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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

		Schema::table('user_profile_notes', function(Blueprint $table)
		{

			$table->dropForeign('user_profile_notes_user_id_foreign');
			$table->dropForeign('user_profiles_notes_user_profile_id_foreign');

		});

		Schema::dropIfExists('user_profile_notes');

	}
}
