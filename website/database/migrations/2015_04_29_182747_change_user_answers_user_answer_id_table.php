<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserAnswersUserAnswerIdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('user_answers', function($table)
		{

			$table->integer('referent_id')->unsigned()->nullable();
			$table->string('to_referent_slug');

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

			$table->dropColumn('referent_id');
			$table->dropColumn('to_referent_slug');

		});

	}


}
