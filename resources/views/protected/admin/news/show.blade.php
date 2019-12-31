@extends('protected.admin.master')

@section('title', 'Show detail')

@section('content')
@if(!empty($news))
<h2>View detail news ID({{$news->news_id}})</h2>
<table class="table table-striped table-bordered table-hover">

    <tr>
        <th>News ID</th>
        <td>{{ $news->id }}</td>
    </tr>
    <tr>
        <th>News title</th>
        <td>{{ $news->title }}</td>
    </tr>
    <tr>
        <th>News description</th>
        <td>{{ $news->description }}</td>
    </tr>
    <tr>
        <th>News content</th>
        <td>{!! $news->detail !!}</td>
    </tr>
    <tr>
        <th>News Created date</th>
        <td>{{$news->created_at}}</td>
    </tr>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
@stop