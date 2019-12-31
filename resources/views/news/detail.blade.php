@extends('master')

@section('content')
<div class="news-wapper">
    <h1>{{$news->title}}</h1>
    <div class="news-item-content">
        <div class="news-content">{!! $news->detail !!}</div>
    </div>
</div>
@endsection