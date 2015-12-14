<?php

/**
 * We take the matching page from the pages table (with the `slug`)
 */
Html::macro('page', function($slug)
{
  $page = Page::where('slug', $slug)->first();

  if ($page)
  {
    return $page->content;
  }

});
