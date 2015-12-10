<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnerImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_images', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('partner_id')->unsigned()->nullable();
			$table->text('folder');
			$table->text('filename');

			// Indexes
			$table->index('partner_id');

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

		Schema::dropIfExists('partner_images');

	}


}
