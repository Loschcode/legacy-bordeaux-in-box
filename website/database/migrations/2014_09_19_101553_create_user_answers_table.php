<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_answers', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('box_question_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();

			// Fields
			$table->text('answer');

			// Indexes
			$table->foreign('box_question_id')->references('id')->on('box_questions')->onDelete('cascade');
			$table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');

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

		Schema::table('user_answers', function(Blueprint $table)
		{

			$table->dropForeign('user_answers_box_question_id_foreign');
			$table->dropForeign('user_answers_user_profile_id_foreign');

		});

		Schema::dropIfExists('user_answers');

	}


}