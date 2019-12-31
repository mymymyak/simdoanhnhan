@extends('protected.admin.master')

@section('title', 'View page')

@section('content')
@if(!empty($page))
<h2>View detail page ID({{$page->id}})</h2>
<table class="table table-striped table-bordered table-hover">
    <tr>
        <th>ID</th>
        <td>{{ $page->id }}</td>
    </tr>
    <tr>
        <th>Title</th>
        <td>{{ $page->title }}</td>
    </tr>
    <tr>
        <th>Content</th>
        <td>{{ $page->detail }}</td>
    </tr>
    <tr>
        <th>Blog Created date</th>
        <td>{{ date('d/m/Y', strtotime($page->created_at)) }}</td>
    </tr>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
@stop