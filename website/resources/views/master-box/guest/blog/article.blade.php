@extends('master-box.layouts.master')
@section('content')
	
	<div class="container blog">

		<div class="blog-post">
			<div class="col-md-8 col-md-offset-2">

				<a class="blog-title" href="{{url('/blog/article/'.$blog_article->id)}}">{{ $blog_article->title }}</a><br />

				<img class="img-responsive thumbnail blog-align" src="{{ url($blog_article->thumbnail->full) }}">

				<div class="blog-content">
					{!! nl2br($blog_article->content) !!}
				</div>
			</div>

			<div class="clearfix"></div>
		</div>

		<div class="spacer20"></div>

		<div class="text-center">

			<div class="col-md-4 col-md-offset-4">
				@if ($previous_article !== NULL || $next_article !== NULL)
					<nav>
					  <ul class="pager">
					  	@if ($previous_article !== NULL)
					  		<li class="previous"><a href="{{url('blog/article/'.$previous_article->id)}}">&larr; {{$previous_article->title}}</a></li>
					  	@else
					  		<li class="previous disabled"><a href="#">&larr; Ancien</a></li>
					  	@endif

					  	@if ($next_article !== NULL)
					    	<li class="next"><a href="{{url('blog/article/'.$next_article->id)}}">{{$next_article->title}}  &rarr;</a></li>
					  	@else
					  		<li class="next disabled"><a href="#">Suivant &rarr;</a></li>
					  	@endif
					  </ul>
					</nav>
				@endif
			</div>
		</div>

		<div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = 'lapetitebox'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>


		<div class="spacer100"></div>

	</div>

	@include('master-box.partials.footer')

@stop