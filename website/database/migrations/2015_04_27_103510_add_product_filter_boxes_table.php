<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductFilterBoxesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_filter_boxes', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('box_id')->unsigned()->nullable();
			$table->integer('partner_product_id')->unsigned()->nullable();

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

		Schema::dropIfExists('product_filter_boxes');

	}


}
