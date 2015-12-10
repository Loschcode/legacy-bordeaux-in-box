<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('payments', function($table)
		{

			// Keys
			$table->integer('order_id')->unsigned()->nullable();

			// Indexes
			$table->foreign('order_id')->references('id')->on('orders');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('payments', function($table)
		{

			// Foreign
			$table->dropForeign('payments_order_id_foreign');

			// Columns to remove
			$table->dropColumn('order_id');

		});

	}

}
