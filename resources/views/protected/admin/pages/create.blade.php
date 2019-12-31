@extends('protected.admin.master')

@section('title', 'Create Pages')

@section('content')

<h2>Create Page</h2>
{!! Form::open(['route' => 'pages.store']) !!}
<!-- Title -->
<div class="col-lg-9">
    <div class="form-group {!! $errors->has('title') ? 'has-error' : '' !!}">
        <label class="control-label" for="title">Page title</label>
        <div class="controls">
            {!! Form::text('title', null, array('class'=>'form-control', 'id' => 'title', 'placeholder'=>'Title', 'value'=>Input::old('title'))) !!}
            @if ($errors->first('title'))
            <span class="help-block">{!! $errors->first('title') !!}</span>
            @endif
        </div>
    </div>
    <div class="form-group {!! $errors->has('detail') ? 'has-error' : '' !!}">
        <label class="control-label" for="detail">Page Content</label>
        <div class="controls">
            {!! Form::textarea('detail', null, array('class'=>'form-control tinymce', 'id' => 'detail', 'placeholder'=>'Content', 'value'=>Input::old('detail'))) !!}
            @if ($errors->first('detail'))
            <span class="help-block">{!! $errors->first('detail') !!}</span>
            @endif
        </div>
    </div>
</div>
<div class="col-lg-3">
    <label class="control-label" for="blog_content">Option</label>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">All domain</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <div class="">
                    <label>
                        {{ Form::checkbox('flag_all',Input::old('flag_all', 1)) }} For all
                    </label>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

</div>
{!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop