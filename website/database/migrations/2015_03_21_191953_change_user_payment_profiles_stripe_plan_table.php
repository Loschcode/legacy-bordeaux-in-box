<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserPaymentProfilesStripePlanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('user_payment_profiles', function($table)
		{

			// Keys
			$table->renameColumn('stripe_plan_id', 'stripe_subscription');
			$table->string('stripe_plan');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('user_payment_profiles', function($table)
		{

			// Columns to remove
			$table->dropColumn('stripe_plan');
			$table->renameColumn('stripe_subscription', 'stripe_plan_id');
			
		});

	}

}
