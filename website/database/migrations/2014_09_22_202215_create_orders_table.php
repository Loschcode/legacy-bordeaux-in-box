<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('user_profile_id')->unsigned()->nullable();
			$table->integer('box_id')->unsigned()->nullable();
			$table->integer('delivery_serie_id')->unsigned()->nullable();
			$table->integer('delivery_spot_id')->unsigned()->nullable();
			
			//$table->integer('payment_id')->unsigned()->nullable();

			// Fields
			$table->enum('status', array('paid', 'canceled', 'unpaid', 'failed', 'half-paid', 'scheduled', 'ready', 'problem', 'delivered', 'packing'));
			$table->boolean('gift');
			$table->boolean('locked');
			$table->boolean('take_away');
			$table->float('unity_and_fees_price');
			$table->float('already_paid');

			// Indexes
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('box_id')->references('id')->on('boxes')->onDelete('cascade');
			$table->foreign('user_profile_id')->references('id')->on('user_profiles')->onDelete('cascade');
			$table->foreign('delivery_serie_id')->references('id')->on('delivery_series')->onDelete('cascade');
			
			//$table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
			// No cascade for delivery spot

			// Timestamps
			$table->date('date_completed')->nullable();
			$table->date('date_sent')->nullable();
			
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

		Schema::table('orders', function(Blueprint $table)
		{

			$table->dropForeign('orders_user_id_foreign');
			$table->dropForeign('orders_box_id_foreign');
			$table->dropForeign('orders_user_profile_id_foreign');
			$table->dropForeign('orders_delivery_serie_id_foreign');

		});

		Schema::dropIfExists('orders');

	}

}
