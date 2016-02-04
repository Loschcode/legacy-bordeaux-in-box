<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\BlogArticle;

class MasterBox_Guest_BlogControllerTest extends TestCase
{

  use DatabaseTransactions;
  use MailTracking;

  /** @test */
  public function should_see_articles()
  {
    $this->visit('blog')
      ->seePageIs('blog');

    $this->assertViewHas('blog_articles');

  }

  /** @test */
  public function should_see_article()
  {
    $article = factory(BlogArticle::class)->create();

    $this->visit('blog/' . $article->slug)
      ->seePageIs('blog/' . $article->slug);

    $this->assertViewHas('blog_article');
    $this->assertViewHas('random_articles');
  }

  /** @test */
  public function should_redirect_blog_index_if_article_do_not_exist()
  {
    $this->visit('blog/wrong-slug')
      ->seePageIs('/blog');
  }

}
