@extends('protected.admin.master')

@section('title', 'List Blogs')

@section('content')

<h2>List Blogs</h2>
<div class="pull-right">
    <a href="{{route('blog.create')}}" class="btn btn-info">Add new</a>
</div>
<div class="ajax-content">
@include('protected/admin/blogs/list')
</div>
@stop