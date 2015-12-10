<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPartnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('partners', function($table)
		{

			// Keys
			$table->increments('id');

			// Fields
			$table->integer('blog_article_id')->unsigned()->nullable();
			$table->string('name');
			$table->string('slug');
			$table->text('description');
			$table->text('website');
			$table->text('facebook');

			// Indexes
			$table->index('blog_article_id');
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

		Schema::dropIfExists('partners');

	}

}
