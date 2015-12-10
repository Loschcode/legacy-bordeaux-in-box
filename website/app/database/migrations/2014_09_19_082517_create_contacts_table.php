<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->string('email');
			$table->string('recipient');
			$table->string('service');
			$table->text('message');

			// Indexes
			$table->index('email');

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

		Schema::dropIfExists('contacts');

	}
	
}
