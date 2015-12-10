<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserAnswersSlugTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('user_answers', function($table)
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

		Schema::table('user_answers', function($table)
		{

			// Columns to remove
			$table->dropColumn('slug');

		});

	}

}
