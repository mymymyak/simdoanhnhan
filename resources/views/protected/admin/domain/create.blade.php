@extends('protected.admin.master')

@section('title', 'Create Domain')

@section('content')

<h2>Create New</h2>
{!! Form::open(['route' => 'domain.store']) !!}
<!-- Title -->
<div class="col-lg-9">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Options</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('domain') ? 'has-error' : '' !!}">
                        <label class="control-label" for="news_name">Domain Url (Ex: sim123.com)</label>
                        <div class="controls">
                            {!! Form::text('domain', null, array('class'=>'form-control', 'id' => 'domain', 'placeholder'=>'Domain Url', 'value'=>Input::old('domain'))) !!}
                            @if ($errors->first('domain'))
                                <span class="help-block">{!! $errors->first('domain') !!}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('domain_name') ? 'has-error' : '' !!}">
                        <label class="control-label" for="news_name">Domain Name</label>
                        <div class="controls">
                            {!! Form::text('domain_name', null, array('class'=>'form-control', 'id' => 'domain_name', 'placeholder'=>'Domain Name', 'value'=>Input::old('domain_name'))) !!}
                            @if ($errors->first('domain_name'))
                                <span class="help-block">{!! $errors->first('domain_name') !!}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
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
                </div>
                <div class="col-md-6">
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
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('logo_mobile') ? 'has-error' : '' !!}">
                        <label class="control-label" for="logo">Logo</label>
                        <div class="controls">
                            <div class="input-group">
                                @if ($errors->first('logo_mobile'))
                                    <span class="help-block">{!! $errors->first('logo_mobile') !!}</span>
                                @endif
                                {!! Form::text('logo_mobile', null, array('class'=>'form-control',
                                'id' => 'logo_mobile', 'placeholder'=>'Image', 'value'=>Input::old('logo_mobile'))) !!}
                                <div class="input-group-btn">
                                    <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=logo_mobile')" class="btn btn-danger" type="button">Select</a>
                                </div>
                            </div>
                            <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;display: none"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group {!! $errors->has('header_code') ? 'has-error' : '' !!}">
                <label class="control-label" for="header_code">Header</label>
                <div class="controls">
                    @if ($errors->first('header_code'))
                        <span class="help-block">{!! $errors->first('header_code') !!}</span>
                    @endif
                    {!! Form::textarea('header_code', null, array('class'=>'form-control', 'id' => 'header_code', 'placeholder'=>'Header', 'value'=>Input::old('header_code'))) !!}
                </div>
            </div>
            <div class="form-group {!! $errors->has('hotro_khachhang') ? 'has-error' : '' !!}">
                <label class="control-label" for="hotro_khachhang">hỗ trợ khách hàng</label>
                <div class="controls">
                    @if ($errors->first('hotro_khachhang'))
                        <span class="help-block">{!! $errors->first('hotro_khachhang') !!}</span>
                    @endif
                    {!! Form::textarea('hotro_khachhang', null, array('class'=>'form-control', 'height' => 2,
                     'id' => 'hotro_khachhang', 'placeholder'=>'Hỗ trợ khách hàng', 'value'=>Input::old('hotro_khachhang'))) !!}
                </div>
            </div>
            <div class="form-group {!! $errors->has('footer_code') ? 'has-error' : '' !!}">
                <label class="control-label" for="header_code">Footer</label>
                <div class="controls">
                    @if ($errors->first('footer_code'))
                        <span class="help-block">{!! $errors->first('footer_code') !!}</span>
                    @endif
                    {!! Form::textarea('footer_code', null, array('class'=>'form-control', 'height' => 2,
                     'id' => 'footer_code', 'placeholder'=>'Footer Script', 'value'=>Input::old('footer_code'))) !!}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('home_shortcode') ? 'has-error' : '' !!}">
                        <label class="control-label" for="header_code">HomePage ShortCode</label>
                        <div class="controls">
                            @if ($errors->first('home_shortcode'))
                                <span class="help-block">{!! $errors->first('home_shortcode') !!}</span>
                            @endif
                            {!! Form::textarea('home_shortcode',null,
                            array('class'=>'form-control', 'id' => 'home_shortcode',
                             'placeholder'=>'HomePage ShortCode', 'value'=>Input::old('home_shortcode'))) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-3">
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Public</h3>
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
                    {{ Form::checkbox('active',Input::old('active', 1)) }} Active
                </div>
            </div>
            <div class="form-group">
                <div class="">
                    {{ Form::checkbox('highlights_number',Input::old('highlights_number', 1)) }} Nổi bật số đầu tiên
                </div>
            </div>
            <div class="form-group {!! $errors->has('template') ? 'has-error' : '' !!}">
                <label class="control-label" for="template">Template</label>
                <div class="controls">
                    {!! Form::select('template', $template, null, array('class'=>'form-control', 'id' => 'template')) !!}
                    @if ($errors->first('template'))
                        <span class="help-block">{!! $errors->first('template') !!}</span>
                    @endif
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
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('hotline_open') ? 'has-error' : '' !!}">
                        <label class="control-label" for="hotline_open">Hotline Open</label>
                        <div class="controls">
                            {!! Form::number('hotline_open', null, array('min' => 0, 'max' => 24, 'class'=>'form-control', 'id' => 'hotline_open', 'placeholder'=>'Open', 'value'=>Input::old('hotline_open'))) !!}
                            @if ($errors->first('hotline_open'))
                                <span class="help-block">{!! $errors->first('hotline_open') !!}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {!! $errors->has('hotline_close') ? 'has-error' : '' !!}">
                        <label class="control-label" for="hotline_close">Hotline Close</label>
                        <div class="controls">
                            {!! Form::number('hotline_close', null, array('min' => 0, 'max' => 24, 'class'=>'form-control', 'id' => 'hotline_close', 'placeholder'=>'Close', 'value'=>Input::old('hotline_close'))) !!}
                            @if ($errors->first('hotline_close'))
                                <span class="help-block">{!! $errors->first('hotline_close') !!}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
			<div class="form-group {!! $errors->has('condau') ? 'has-error' : '' !!}">
				<label class="control-label" for="condau">Con dấu (PNG 321 x 400)</label>
				<div class="controls">
					<div class="input-group">
						@if ($errors->first('condau'))
							<span class="help-block">{!! $errors->first('condau') !!}</span>
						@endif
						{!! Form::text('condau', null, array('class'=>'form-control', 'id' => 'condau', 'placeholder'=>'Image', 'value'=>Input::old('condau'))) !!}
						<div class="input-group-btn">
							<a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=condau')" class="btn btn-danger" type="button">Select</a>
						</div>
					</div>
					<div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;display: none"></div>
				</div>
			</div>
        </div>
        <!-- /.box-body -->
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
{!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop
