<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailTracesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('email_traces', function($table)
		{
 
			// Keys
			$table->increments('id');

			// Fields
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();
			
			$table->string('mailgun_message_id');

			$table->string('recipient');
			$table->string('subject');
			$table->text('content');

			$table->datetime('prepared_at')->nullable();
			$table->datetime('delivered_at')->nullable();
			$table->datetime('first_opened_at')->nullable();
			$table->datetime('last_opened_at')->nullable();

			// Indexes
			$table->index('user_id');
			$table->index('user_profile_id');

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

		Schema::dropIfExists('email_traces');

	}


}
