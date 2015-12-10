<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductFilterBoxAnswersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('product_filter_box_answers', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('partner_product_id')->unsigned()->nullable();
			$table->integer('box_question_id')->unsigned()->nullable();

			$table->string('answer');
			$table->string('slug');
			$table->string('to_referent_slug');

			// Indexes
			$table->index('partner_product_id');
			$table->index('box_question_id');

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

		Schema::dropIfExists('product_filter_box_answers');

	}

}
