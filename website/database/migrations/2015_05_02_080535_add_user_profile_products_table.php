<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserProfileProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_profile_products', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('user_profile_id')->unsigned()->nullable();
			$table->integer('serie_product_id')->unsigned()->nullable();
			$table->integer('order_id')->unsigned()->nullable();
			$table->integer('partner_product_id')->unsigned()->nullable();

			//$table->enum('priority', ['high', 'medium', 'low']);

			$table->integer('already_got');

			$table->boolean('birthday');
			$table->boolean('sponsor');
			$table->integer('accuracy');

			// Indexes
			$table->index('user_profile_id');
			$table->index('serie_product_id');
			$table->index('partner_product_id');
			$table->index('order_id');

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

		Schema::dropIfExists('user_profile_products');

	}


}
