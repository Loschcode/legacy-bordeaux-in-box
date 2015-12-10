<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function($table)
		{

			// Keys
			$table->increments('id');
			//$table->integer('order_id')->unsigned()->nullable();
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();

			// Fields
			$table->string('stripe_event');
			$table->string('stripe_customer');
			$table->string('stripe_charge');
			$table->string('stripe_card');
			$table->string('type');
			$table->boolean('paid');
			$table->string('last4');
			$table->float('amount');

			$table->string('bill_id');
			
			// Indexes
			//$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');

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

		Schema::table('payments', function(Blueprint $table)
		{

			//$table->dropForeign('payments_order_id_foreign');
			$table->dropForeign('payments_user_id_foreign');
			$table->dropForeign('payments_user_profile_id_foreign');

		});

		Schema::dropIfExists('payments');

	}


}
