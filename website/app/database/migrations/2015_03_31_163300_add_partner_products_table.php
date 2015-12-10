<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnerProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partner_products', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('partner_id')->unsigned()->nullable();

			$table->string('name');
			$table->string('slug');

			$table->enum('type', array('maximum', 'medium', 'minimum'));

			$table->text('description');

			$table->float('weight');

			// Indexes
			$table->index('partner_id');
			$table->index('slug');

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

		Schema::dropIfExists('partner_products');

	}

}
