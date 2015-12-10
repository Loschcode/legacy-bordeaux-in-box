<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUserPaymentProfilesLast4Table extends Migration {

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
			$table->string('last4');

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
			$table->dropColumn('last4');

		});

	}

}
