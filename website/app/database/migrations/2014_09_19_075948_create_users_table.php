<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->string('email');
			$table->string('password');
			$table->enum('role', array('admin', 'user'));

			$table->string('remember_token');

			$table->string('first_name');
			$table->string('last_name');

			$table->string('phone');

			$table->text('address');
			$table->string('zip');
			$table->string('city');

			//$table->string('stripe_customer');
			
			// Indexes
			$table->unique('email');

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

		Schema::dropIfExists('users');
		
	}

}
