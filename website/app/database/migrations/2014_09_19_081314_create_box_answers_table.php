<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('box_answers', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('box_question_id')->unsigned()->nullable();

			// Fields
			$table->string('content');

			// Indexes
			$table->foreign('box_question_id')->references('id')->on('box_questions')->onDelete('cascade');

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

		Schema::table('box_answers', function(Blueprint $table)
		{

			$table->dropForeign('box_answers_box_question_id_foreign');

		});

		Schema::dropIfExists('box_answers');

	}

}
