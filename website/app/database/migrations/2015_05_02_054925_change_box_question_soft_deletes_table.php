<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxQuestionSoftDeletesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('box_questions', function($table)
		{

			// Soft deletes
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

		Schema::table('box_questions', function(Blueprint $table)
		{

			// Soft deletes
			$table->dropSoftDeletes();

		});


	}

}
