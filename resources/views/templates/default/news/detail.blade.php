@extends($templateName.'.master')

@section('content')
<div class="news-wapper">
    <h1>{{$news->title}}</h1>
    <div class="news-item-content">
        <div class="news-content">{!! $news->detail !!}</div>
    </div>
</div>
@if($related->count())
<div class="news-wapper related">
	<h3 class="related-title">Bài viết liên quan</h3>
	<ul class="related-news">
		@foreach($related as $newsItem)
			<li>
				<a href="{{ route('frontend.news.detail', ['slug' => $newsItem->slug]) }}" title="{{$newsItem->title}}">
				<img src="/frontend/images/placeholder.jpg" class="img-fluid" alt="{{$newsItem->title}}">
				<p>{{$newsItem->title}}</p></a>
			</li>
        @endforeach
	</ul>
</div>
@endif
@endsection