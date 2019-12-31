@extends('protected.admin.master')

@section('title', 'Create Blog')

@section('content')

<h2>Create Blog</h2>
{!! Form::open(['route' => 'blog.store']) !!}
<!-- Title -->
<div class="col-lg-9">
    <div class="form-group {!! $errors->has('blog_title') ? 'has-error' : '' !!}">
        <label class="control-label" for="blog_title">Blog title</label>
        <div class="controls">
            {!! Form::text('blog_title', null, array('class'=>'form-control', 'id' => 'blog_title', 'placeholder'=>'Title', 'value'=>Input::old('blog_title'))) !!}
            @if ($errors->first('blog_title'))
            <span class="help-block">{!! $errors->first('blog_title') !!}</span>
            @endif
        </div>
    </div>
    <div class="form-group {!! $errors->has('blog_des') ? 'has-error' : '' !!}">
        <label class="control-label" for="blog_des">Blog description</label>
        <div class="controls">
            {!! Form::text('blog_des', null, array('class'=>'form-control', 'id' => 'blog_des', 'placeholder'=>'Description', 'value'=>Input::old('blog_des'))) !!}
            @if ($errors->first('blog_des'))
            <span class="help-block">{!! $errors->first('blog_des') !!}</span>
            @endif
        </div>
    </div>
    <div class="form-group {!! $errors->has('blog_content') ? 'has-error' : '' !!}">
        <label class="control-label" for="blog_content">Blog Content</label>
        <div class="controls">
            {!! Form::textarea('blog_content', null, array('class'=>'form-control', 'id' => 'blog_content', 'placeholder'=>'Content', 'value'=>Input::old('blog_content'))) !!}
            @if ($errors->first('blog_content'))
            <span class="help-block">{!! $errors->first('blog_content') !!}</span>
            @endif
        </div>
    </div>
</div>
<div class="col-lg-3">
    <label class="control-label" for="blog_content">Option</label>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Category</h3>

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
                        <input type="checkbox" /> Cat 1
                    </label>
                    <label>
                        <input type="checkbox" /> Cat 2
                    </label>
                    <label>
                        <input type="checkbox" /> Cat 3
                    </label>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Tag</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <input class="form-control" type="text" placeholder="Tag" />
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Feature Image</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <input type="file" />
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">List carousel image</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
            <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="form-group">
                <input type="file" />
            </div>
        </div>
        <!-- /.box-body -->
    </div>

</div>
{!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop