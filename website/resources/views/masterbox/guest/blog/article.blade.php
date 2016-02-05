@extends('masterbox.layouts.master')

@section('gotham')
  {!! Html::gotham([
    'controller' => 'masterbox.guest.blog.article',
    'text' => $blog_article->title,
    'pinterest-media' => $blog_article->thumbnail->full
  ]) !!}
@stop

@section('content')

<div class="container">
  <div class="article article__wrapper">

    <div class="grid-8 grid-centered grid-11@xs">
      <div class="article__cover-container">
        <img class="article__cover" src="{{ $blog_article->thumbnail->full }}" />
      </div>
    </div>
    <div class="grid-10 grid-centered">
      <div class="article__content typography">
        {!! Markdown::convertToHtml($blog_article->content) !!}
      </div>
  
      <div class="+spacer"></div>
      
      <div id="share"></div>

      <div class="+spacer"></div>

      @foreach ($random_articles->chunk(4) as $chunk)
        <div class="row row-align-center@xs">
          @foreach ($chunk as $article)
            <div class="grid-3 grid-12@xs">
              <div class="partner">
                <div class="partner__picture-container">
                  <a href="{{ action('MasterBox\Guest\BlogController@getArticle', ['slug' => $article->slug]) }}">
                    <img class="partner__picture" src="{{ Html::resizeImage('medium', $article->thumbnail->filename) }}" />
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endforeach

      <div class="+spacer-small"></div>


      <div id="disqus_thread"></div>
      <script type="text/javascript">
        var disqus_shortname = 'lapetitebox'; // required: replace example with your forum shortname

        (function() {

          var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
          dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
          (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
      </script>
      <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

    </div>
  </div>
</div>

@stop