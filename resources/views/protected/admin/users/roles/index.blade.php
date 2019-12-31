@extends('protected.admin.master')

@section('title', 'List Roles')

@section('content')

<h2>List Roles</h2>
<div class="pull-right"><a href="{{route('role.create')}}" class="btn btn-info">Add new</a></div>
@include('protected/admin/users/roles/list')
@stop