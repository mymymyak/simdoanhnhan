@extends('protected.admin.master')

@section('title', 'Create Role')

@section('content')

<h2>Create Role</h2>
{!! Form::open(['route' => 'role.store']) !!}
<!-- Title -->
<div class="form-group {!! $errors->has('name') ? 'has-error' : '' !!}">
    <label class="control-label" for="name">Role name</label>
    <div class="controls">
        {!! Form::text('name', null, array('class'=>'form-control', 'id' => 'name', 'placeholder'=>'Name', 'value'=>Input::old('name'))) !!}
        @if ($errors->first('name'))
        <span class="help-block">{!! $errors->first('name') !!}</span>
        @endif
    </div>
</div>
<div class="form-group {!! $errors->has('slug') ? 'has-error' : '' !!}">
    <label class="control-label" for="slug">Role slug</label>
    <div class="controls">
        {!! Form::text('slug', null, array('class'=>'form-control', 'id' => 'slug', 'placeholder'=>'Slug', 'value'=>Input::old('slug'))) !!}
        @if ($errors->first('slug'))
        <span class="help-block">{!! $errors->first('slug') !!}</span>
        @endif
    </div>
</div>
<div class="row">
    @foreach ($routeList as $route) 
    <div class="col-lg-3">
        <label>
            {!! Form::checkbox('permissions[]', $route, 0) !!} {{ $route }}
        </label>
    </div>
    @endforeach
</div>
{!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop
