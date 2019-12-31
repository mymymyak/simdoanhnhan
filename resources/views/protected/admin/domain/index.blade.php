@extends('protected.admin.master')

@section('title', 'List Domain')

@section('content')
    <div class="pull-right">
        <a href="{{route('domain.create')}}" class="btn btn-info">Add new</a>
    </div>
    <div class="ajax-content">
        @include('protected/admin/domain/list')
    </div>
@stop