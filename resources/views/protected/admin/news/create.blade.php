@extends('protected.admin.master')

@section('title', 'Create New')

@section('content')

<h2>Create New</h2>
{!! Form::open(['route' => 'news.store']) !!}
<!-- Title -->
<div class="row">
    <div class="col-lg-9">
        <div class="form-group {!! $errors->has('title') ? 'has-error' : '' !!}">
            <label class="control-label" for="title">Title</label>
            <div class="controls">
                {!! Form::text('title', null, array('class'=>'form-control', 'id' => 'title', 'placeholder'=>'Title', 'value'=>Input::old('title'))) !!}
                @if ($errors->first('title'))
                    <span class="help-block">{!! $errors->first('title') !!}</span>
                @endif
            </div>
        </div>
        <div class="form-group {!! $errors->has('description') ? 'has-error' : '' !!}">
            <label class="control-label" for="description">Description</label>
            <div class="controls">
                {!! Form::textarea('description', null, array('class'=>'form-control', 'id' => 'description', 'placeholder'=>'Description', 'value'=>Input::old('description'))) !!}
                @if ($errors->first('description'))
                    <span class="help-block">{!! $errors->first('description') !!}</span>
                @endif
            </div>
        </div>
        <div class="form-group {!! $errors->has('detail') ? 'has-error' : '' !!}">
            <label class="control-label" for="detail">Content</label>
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
                <h3 class="box-title">Feature Image</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
                <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="input-group">
                    @if ($errors->first('pic'))
                        <span class="help-block">{!! $errors->first('pic') !!}</span>
                    @endif
                    {!! Form::text('pic', null, array('class'=>'form-control', 'id' => 'fieldID1', 'placeholder'=>'Image', 'value'=>Input::old('pic'))) !!}
                    <div class="input-group-btn">
                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=fieldID1')" class="btn btn-danger" type="button">Select</a>
                    </div>
                </div>
                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;display: none"></div>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<script>
    function responsive_filemanager_callback(field_id){
        var url=jQuery('#'+field_id).val();
        //alert('update '+field_id+" with "+url);
        jQuery('.preview-image').html('<img src="'+url+'" width="100" />').css('display','block');
    }
    function open_popup(url)
    {
        var w = 880;
        var h = 570;
        var l = Math.floor((screen.width-w)/2);
        var t = Math.floor((screen.height-h)/2);
        var win = window.open(url, 'ResponsiveFilemanager', "scrollbars=1,width=" + w + ",height=" + h + ",top=" + t + ",left=" + l);
    }
</script>
<div class="clearfix"></div>
{!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop