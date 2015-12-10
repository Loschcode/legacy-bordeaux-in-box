<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogArticlesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('blog_articles', function($table)
		{

			// Keys
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();

			// Fields
			$table->string('title');
			$table->string('slug');
			$table->string('url');
			$table->text('content');
			$table->text('thumbnail');

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

		Schema::table('blog_articles', function(Blueprint $table)
		{

			$table->dropForeign('blog_articles_user_id_foreign');

		});

		Schema::dropIfExists('blog_articles');

	}

}
