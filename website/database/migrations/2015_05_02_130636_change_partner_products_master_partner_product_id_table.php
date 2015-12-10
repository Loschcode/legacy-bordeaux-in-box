<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePartnerProductsMasterPartnerProductIdTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('partner_products', function($table)
		{

			$table->integer('master_partner_product_id')->nullable();
			$table->index('master_partner_product_id');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

		Schema::table('partner_products', function(Blueprint $table)
		{

			$table->dropColumn('master_partner_product_id');

		});


	}

}
