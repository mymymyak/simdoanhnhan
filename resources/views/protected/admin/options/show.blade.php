@extends('protected.admin.master')

@section('title', 'Show detail')

@section('content')
@if(!empty($news))
<h2>View detail news ID({{$news->news_id}})</h2>
<table class="table table-striped table-bordered table-hover">

    <tr>
        <th>News ID</th>
        <td>{{ $news->news_id }}</td>
    </tr>
    <tr>
        <th>News title</th>
        <td>{{ $news->news_name }}</td>
    </tr>
    <tr>
        <th>News description</th>
        <td>{{ $news->news_des }}</td>
    </tr>
    <tr>
        <th>News Created date</th>
        <td></td>
    </tr>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
@stop