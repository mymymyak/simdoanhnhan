@extends($templateName.'.master')

@section('content')
<div class="">
    <h1 style="margin: 0;padding: 15px 15px 10px 15px;background: #fff;border-bottom: 1px solid #259d99;">Tin Tức Sim Số</h1>
    <div class="news-item-content">
        <div class="news-content">
            @if ($news->count())
                @foreach($news as $newsItem)
            <div class="item-list">
                <div class="item-list-img">
                    <amp-img src="/frontend/images/placeholder.jpg" class="img-fluid"
                             alt="{{$newsItem->title}}" width="{{ isMobile() ? "106" :"210" }}"
                             height="{{ isMobile() ? "59" :"117" }}"></amp-img>
                </div>
                <div class="item-list-info">
                    <h3 class="item-list-title"><a href="{{ route('frontend.news.detail',
                     ['slug' => $newsItem->slug,'id' => $newsItem->id]) }}"
                                                   title="{{$newsItem->title}}">{{$newsItem->title}}</a></h3>
                    <div class="time-label">{{ date('d/m/Y', strtotime($newsItem->created_at)) }}</div>
                    <div class="short-des">{{ substr_word($newsItem->description, 200) }}</div>
                </div>
            </div>
                @endforeach
            @endif
        </div>
        <div class="pagination">
            {{$news->links()}}
        </div>
    </div>
</div>
@endsection
