@extends('protected.admin.master')

@section('title', 'List Pages')

@section('content')

<h2>List Pages</h2>
<div class="pull-right">
    <a href="{{route('pages.create')}}" class="btn btn-info">Add new</a>
</div>
<div class="ajax-content">
@include('protected/admin/pages/list')
</div>
@stop