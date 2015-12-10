<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('image_articles', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();

			// Fields
			$table->string('title');
			$table->string('slug');
			$table->text('description');
			$table->string('image');

			// Indexes
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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

		Schema::table('image_articles', function(Blueprint $table)
		{

			$table->dropForeign('image_articles_user_id_foreign');

		});

		Schema::dropIfExists('image_articles');

	}

}
