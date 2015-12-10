<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('box_questions', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('box_id')->unsigned()->nullable();

			// Fields
			$table->string('question');
			$table->string('type');

			// Indexes
			$table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');

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

		Schema::table('box_questions', function(Blueprint $table)
		{

			$table->dropForeign('box_questions_box_id_foreign');

		});

		Schema::dropIfExists('box_questions');

	}
}
