<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDestinationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('order_destinations', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('order_id')->unsigned()->nullable();

			// Fields
			$table->string('city');
			$table->text('address');
			$table->string('zip');
			$table->string('first_name');
			$table->string('last_name');

			// Indexes
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

			// Timestamps
			$table->date('date_completed');
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

		Schema::table('order_destinations', function(Blueprint $table)
		{

			$table->dropForeign('order_destinations_order_id_foreign');

		});

		Schema::dropIfExists('order_destinations');

	}

}