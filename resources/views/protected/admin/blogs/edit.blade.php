@extends('protected.admin.master')

@section('title', 'Edit Blog ID ' . $blog->blog_id)

@section('content')

<h2>Edit Blog ID({{ $blog->blog_id }})</h2>
{!! Form::open(['route' => ['blog.update', $blog->blog_id], 'method' => 'PUT']) !!}
<!-- Title -->
<div class="form-group {!! $errors->has('blog_title') ? 'has-error' : '' !!}">
    <label class="control-label" for="blog_title">Blog title</label>
    <div class="controls">
        {!! Form::text('blog_title', $blog->blog_title, array('class'=>'form-control', 'id' => 'blog_title', 'placeholder'=>'Title', 'value'=>Input::old('blog_title'))) !!}
        @if ($errors->first('blog_title'))
        <span class="help-block">{!! $errors->first('blog_title') !!}</span>
        @endif
    </div>
</div>
<div class="form-group {!! $errors->has('blog_des') ? 'has-error' : '' !!}">
    <label class="control-label" for="blog_des">Blog description</label>
    <div class="controls">
        {!! Form::text('blog_des', $blog->blog_des, array('class'=>'form-control', 'id' => 'blog_des', 'placeholder'=>'Description', 'value'=>Input::old('blog_des'))) !!}
        @if ($errors->first('blog_des'))
        <span class="help-block">{!! $errors->first('blog_des') !!}</span>
        @endif
    </div>
</div>
<div class="form-group {!! $errors->has('blog_content') ? 'has-error' : '' !!}">
    <label class="control-label" for="blog_content">Blog Content</label>
    <div class="controls">
        {!! Form::textarea('blog_content', $blog->blog_content, array('class'=>'form-control', 'id' => 'blog_content', 'placeholder'=>'Content', 'value'=>Input::old('blog_content'))) !!}
        @if ($errors->first('blog_content'))
        <span class="help-block">{!! $errors->first('blog_content') !!}</span>
        @endif
    </div>
</div>
{!! Form::submit('Update', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop