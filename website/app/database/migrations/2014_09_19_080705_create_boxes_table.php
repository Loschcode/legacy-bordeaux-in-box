<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('boxes', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->string('title');
			$table->string('description');
			$table->string('image');
			$table->boolean('active');

			// Index
			$table->index('active');

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

		Schema::dropIfExists('boxes');
		
	}

}
