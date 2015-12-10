<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeBoxQuestionsFilterMustMatchTable extends Migration {

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
			$table->boolean('filter_must_match');

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
			$table->dropColumn('filter_must_match');

		});


	}
	
}
