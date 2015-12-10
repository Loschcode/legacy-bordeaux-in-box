<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePartnerProductsWorthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('partner_products', function($table)
		{

			// Keys
			$table->dropColumn('type');
			$table->enum('size', array('maximum', 'medium', 'minimum'));
			$table->boolean('birthday_ready');
			$table->boolean('sponsor_ready');
			$table->boolean('regional_only');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('partner_products', function($table)
		{

			// Keys
			$table->enum('type', array('maximum', 'medium', 'minimum'));
			$table->dropColumn('size');
			$table->dropColumn('birthday_ready');
			$table->dropColumn('sponsor_ready');
			$table->dropColumn('regional_only');

		});

	}


}
