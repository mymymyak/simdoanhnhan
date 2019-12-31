@extends('protected.admin.master')

@section('title', 'List SEO')

@section('content')
    <div class="pull-right">
        <a href="{{route('news.create')}}" class="btn btn-info">Add new</a>
    </div>
    <div class="ajax-content">
        @include('protected/admin/options/list')
    </div>
@stop