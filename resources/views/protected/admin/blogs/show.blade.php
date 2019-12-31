@extends('protected.admin.master')

@section('title', 'View Blogs')

@section('content')
@if(!empty($blog))
<h2>View detail Page ID({{$blog->blog_id}})</h2>
<table class="table table-striped table-bordered table-hover">

    <tr>
        <th>Blog ID</th>
        <td>{{ $blog->blog_id }}</td>
    </tr>
    <tr>
        <th>Blog title</th>
        <td>{{ $blog->blog_title }}</td>
    </tr>
    <tr>
        <th>Blog description</th>
        <td>{{ $blog->blog_des }}</td>
    </tr>
    <tr>
        <th>Blog Created date</th>
        <td>{{ date('d/m/Y', strtotime($blog->created_at)) }}</td>
    </tr>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
@stop