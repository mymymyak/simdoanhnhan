@extends($templateName.'.master')

@section('content')
<div class="news-wapper">
    <h1>{{$pages->title}}</h1>
    <div class="news-item-content">
        <div class="news-content">{!! $pages->detail !!}</div>
    </div>
</div>
@endsection