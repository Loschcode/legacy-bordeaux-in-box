<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\BlogArticle;
use App\Models\ImageArticle;
use App\Models\Customer;
use App\Models\Administrator;

class ChangeBlogArticlesAndImageArticlesAdministratorId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      $blog_articles = BlogArticle::get();

      foreach ($blog_articles as $blog_article) {

        if ($blog_article->customer_id !== NULL) {
          $author = Customer::find($blog_article->customer_id);
          $administrator_equivalent = Administrator::where('email', '=', $author->email)->first();
        } else {
          $administrator_equivalent = NULL;
        }

        if ($administrator_equivalent === NULL)
          $blog_article->customer_id = NULL;
        else
          $blog_article->customer_id = $administrator_equivalent->id;

        $blog_article->save();

      }

      Schema::table('blog_articles', function ($table) {

        $table->renameColumn('customer_id', 'administrator_id');

      });

      // SAME FOR IMAGE ARTICLES

      $image_articles = ImageArticle::get();

      foreach ($image_articles as $image_article) {

        if ($image_article->customer_id !== NULL) {
          $author = Customer::find($image_article->customer_id);
          $administrator_equivalent = Administrator::where('email', '=', $author->email)->first();
        } else {
          $administrator_equivalent = NULL;
        }

        if ($administrator_equivalent === NULL)
          $image_article->customer_id = NULL;
        else
          $image_article->customer_id = $administrator_equivalent->id;

        $image_article->save();

      }

      Schema::table('image_articles', function ($table) {

        $table->renameColumn('customer_id', 'administrator_id');

      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('blog_articles', function ($table) {
        $table->renameColumn('administrator_id', 'customer_id');
      });
      Schema::table('image_articles', function ($table) {
        $table->renameColumn('administrator_id', 'customer_id');
      });
    }
}
