<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxQuestionsSlugTable extends Migration {

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
			$table->string('slug');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('box_questions', function($table)
		{

			// Columns to remove
			$table->dropColumn('slug');

		});

	}

}
