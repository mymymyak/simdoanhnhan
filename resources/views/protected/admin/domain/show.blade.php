@extends('protected.admin.master')

@section('title', 'Show detail')

@section('content')
@if(!empty($domain))
<h2>View detail domain ID({{$domain->id}})</h2>
<table class="table table-striped table-bordered table-hover">

    <tr>
        <th>ID</th>
        <td>{{ $domain->id }}</td>
    </tr>
    <tr>
        <th>Domain</th>
        <td>{{ $domain->domain }}</td>
    </tr>
    <tr>
        <th>Created</th>
        <td>{{ date('d/m/Y H:i', strtotime($domain->created_at)) }}</td>
    </tr>
    <tr>
        <th>Updated</th>
        <td>{{ date('d/m/Y H:i', strtotime($domain->updated_at)) }}</td>
    </tr>
    <tr>
        <th>Active</th>
        <td>{{ $domain->active == 1 ? 'Active' : 'Disable' }}</td>
    </tr>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
@stop