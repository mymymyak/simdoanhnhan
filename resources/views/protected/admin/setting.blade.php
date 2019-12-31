@extends('protected.admin.master')

@section('title', 'Site Setting')

@section('content')

<h2>Site Setting</h2>
{!! Form::open(['route' => 'managers.site.setting.store']) !!}
<!-- Title -->
<div class="row">
    <div class="col-lg-9">
        <div class="form-group {!! $errors->has('logo') ? 'has-error' : '' !!}">
            <label class="control-label" for="logo">Logo</label>
            <div class="controls">
                <div class="input-group">
                    @if ($errors->first('logo'))
                        <span class="help-block">{!! $errors->first('logo') !!}</span>
                    @endif
                    {!! Form::text('logo', null, array('class'=>'form-control', 'id' => 'logo', 'placeholder'=>'Image', 'value'=>Input::old('logo'))) !!}
                    <div class="input-group-btn">
                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=logo')" class="btn btn-danger" type="button">Select</a>
                    </div>
                </div>
                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;display: none"></div>
            </div>
        </div>
        <div class="form-group {!! $errors->has('favicon') ? 'has-error' : '' !!}">
            <label class="control-label" for="favicon">Favicon</label>
            <div class="controls">
                <div class="input-group">
                    @if ($errors->first('favicon'))
                        <span class="help-block">{!! $errors->first('favicon') !!}</span>
                    @endif
                    {!! Form::text('favicon', null, array('class'=>'form-control', 'id' => 'favicon', 'placeholder'=>'Favicon', 'value'=>Input::old('favicon'))) !!}
                    <div class="input-group-btn">
                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=favicon')" class="btn btn-danger" type="button">Select</a>
                    </div>
                </div>
                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;display: none"></div>
            </div>
        </div>
        <div class="form-group {!! $errors->has('main_color') ? 'has-error' : '' !!}">
            <label class="control-label" for="detail">Main Color</label>
            <div class="controls">
                {!! Form::text('main_color', null, array('class'=>'form-control jscolor {required:false}', 'id' => 'main_color', 'placeholder'=>'Color', 'value'=>Input::old('main_color'))) !!}
                @if ($errors->first('main_color'))
                    <span class="help-block">{!! $errors->first('main_color') !!}</span>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    function responsive_filemanager_callback(field_id){
        var url=jQuery('#'+field_id).val();
        //alert('update '+field_id+" with "+url);
        jQuery('#'+field_id).closest('.form-group').find('.preview-image').html('<img src="'+url+'" width="100" />').css('display','block');
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
{!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop