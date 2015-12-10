<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxQuestionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('box_questions', function($table)
		{

			// Keys
			$table->string('short_question');

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

			// Columns to remove
			$table->dropColumn('short_question');

		});


	}

}
