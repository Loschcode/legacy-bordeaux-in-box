<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_images', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('partner_product_id')->unsigned()->nullable();

			$table->text('folder');
			$table->text('filename');
			
			// Indexes
			$table->index('partner_product_id');

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

		Schema::dropIfExists('product_images');

	}

}
