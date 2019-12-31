@extends('protected.admin.master')

@section('title', 'Edit Domain')

@section('content')

    <h2>Edit domain</h2>
    {!! Form::open(['route' => ['domain.update', $domain->id], 'method' => 'PUT']) !!}
    <!-- Title -->
    <div class="col-lg-9">
        <label class="control-label" for="blog_content">Option</label>
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
                            <label class="control-label" for="news_name">Domain</label>
                            <div class="controls">
                                {!! Form::text('domain', $domain->domain, array('class'=>'form-control', 'id' => 'domain', 'placeholder'=>'Title', 'value'=>Input::old('domain'))) !!}
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
                                {!! Form::text('domain_name', $domain->domain_name, array('class'=>'form-control', 'id' => 'domain_name', 'placeholder'=>'Domain Name', 'value'=>Input::old('domain_name'))) !!}
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
                                    {!! Form::text('logo', isset($config->logo) ? $config->logo : null, array('class'=>'form-control', 'id' => 'logo', 'placeholder'=>'Image', 'value'=>Input::old('logo'))) !!}
                                    <div class="input-group-btn">
                                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=logo')" class="btn btn-danger" type="button">Select</a>
                                    </div>
                                </div>
                                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
                                    @if(isset($config->logo))<img src="{{$config->logo}}" width="100px" />@endif
                                </div>
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
                                    {!! Form::text('favicon', isset($config->favicon) ? $config->favicon : null, array('class'=>'form-control', 'id' => 'favicon', 'placeholder'=>'Favicon', 'value'=>Input::old('favicon'))) !!}
                                    <div class="input-group-btn">
                                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=favicon')" class="btn btn-danger" type="button">Select</a>
                                    </div>
                                </div>
                                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
                                    @if(isset($config->favicon))<img src="{{$config->favicon}}" style="width: 100px" />@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! $errors->has('logo_mobile') ? 'has-error' : '' !!}">
                            <label class="control-label" for="logo">Logo Mobile</label>
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
                                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
                                    @if(isset($config->logo_mobile))<img src="{{$config->logo_mobile}}" width="100px" />@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group {!! $errors->has('header_code') ? 'has-error' : '' !!}">
							<label class="control-label" for="header_code">Header Script</label>
							<div class="controls">
								@if ($errors->first('header_code'))
									<span class="help-block">{!! $errors->first('header_code') !!}</span>
								@endif
								{!! Form::textarea('header_code', $domain->header_code, array('class'=>'form-control', 'rows' => 3, 'id' => 'header_code', 'placeholder'=>'Header script', 'value'=>Input::old('header_code'))) !!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group {!! $errors->has('footer_code') ? 'has-error' : '' !!}">
							<label class="control-label" for="footer_code">Footer Script</label>
							<div class="controls">
								@if ($errors->first('footer_code'))
									<span class="help-block">{!! $errors->first('footer_code') !!}</span>
								@endif
								{!! Form::textarea('footer_code', $domain->footer_code, array('class'=>'form-control',
								'rows' => 3, 'id' => 'footer_code', 'placeholder'=>'Footer Script',
								'value'=>Input::old('footer_code'))) !!}
							</div>
						</div>
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
                                {!! Form::textarea('home_shortcode', $domain->home_shortcode,
                                array('class'=>'form-control', 'id' => 'home_shortcode',
                                 'placeholder'=>'HomePage ShortCode', 'value'=>Input::old('home_shortcode'))) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        [{
                        "title" :  "Sim Doanh Nhân",
                        "items":  20,
                        "customQuery"   : "sim-vip",
                        "customSlug"    : "sim-vip-3-mang",
                        "viewMoreUrl" : "/sim-gia-duoi-500-nghin"
                        },
                        {
                        "title" : "Sim Viettel",
                        "items" : 25,
                        "customQuery":  "sim-viettel",
                        "customSlug"   : "sim-viettel",
                        "viewMoreUrl" : "sim-viettel"
                        }]
                    </div>
                </div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group {!! $errors->has('url_301') ? 'has-error' : '' !!}">
							<label class="control-label" for="url_301">301 Url (Link cũ|Link mới)</label>
							<div class="controls">
								@if ($errors->first('url_301'))
									<span class="help-block">{!! $errors->first('url_301') !!}</span>
								@endif
								{!! Form::textarea('url_301', isset($config->url_301) ? $config->url_301 : null, array('class'=>'form-control', 'rows' => 3, 'id' => 'url_301', 'placeholder'=>'Link cũ|Link mới', 'value'=>Input::old('url_301'))) !!}
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group {!! $errors->has('chat_script') ? 'has-error' : '' !!}">
							<label class="control-label" for="chat_script">Chatbox Script</label>
							<div class="controls">
								@if ($errors->first('chat_script'))
									<span class="help-block">{!! $errors->first('chat_script') !!}</span>
								@endif
								{!! Form::textarea('chat_script', isset($config->chat_script) ? $config->chat_script : null, array('class'=>'form-control', 'rows' => 3, 'id' => 'chat_script', 'placeholder'=>'Chatbox Script', 'value'=>Input::old('chat_script'))) !!}
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group {!! $errors->has('footer_box_1') ? 'has-error' : '' !!}">
							<label class="control-label" for="footer_box_1">Footer box 1</label>
							<div class="controls">
								@if ($errors->first('footer_box_1'))
									<span class="help-block">{!! $errors->first('footer_box_1') !!}</span>
								@endif
								{!! Form::textarea('footer_box_1', isset($config->footer_box_1) ? $config->footer_box_1 : null, array('class'=>'form-control', 'rows' => 4, 'id' => 'footer_box_1', 'placeholder'=>'Footer box 1', 'value'=>Input::old('footer_box_1'))) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group {!! $errors->has('footer_box_2') ? 'has-error' : '' !!}">
							<label class="control-label" for="footer_box_2">Footer box 2</label>
							<div class="controls">
								@if ($errors->first('footer_box_2'))
									<span class="help-block">{!! $errors->first('footer_box_2') !!}</span>
								@endif
								{!! Form::textarea('footer_box_2', isset($config->footer_box_2) ? $config->footer_box_2 : null, array('class'=>'form-control', 'rows' => 4, 'id' => 'footer_box_2', 'placeholder'=>'Footer box 2', 'value'=>Input::old('footer_box_2'))) !!}
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group {!! $errors->has('footer_box_3') ? 'has-error' : '' !!}">
							<label class="control-label" for="footer_box_3">Footer box 3</label>
							<div class="controls">
								@if ($errors->first('footer_box_3'))
									<span class="help-block">{!! $errors->first('footer_box_3') !!}</span>
								@endif
								{!! Form::textarea('footer_box_3', isset($config->footer_box_3) ? $config->footer_box_3 : null, array('class'=>'form-control', 'rows' => 4, 'id' => 'footer_box_3', 'placeholder'=>'Footer box 3', 'value'=>Input::old('footer_box_3'))) !!}
							</div>
						</div>
					</div>
				</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {!! $errors->has('ads_left_url') ? 'has-error' : '' !!}">
                            <label class="control-label" for="ads_left_url">Ads Left Url</label>
                            <div class="controls">
                                @if ($errors->first('ads_left_url'))
                                    <span class="help-block">{!! $errors->first('ads_left_url') !!}</span>
                                @endif
                                {!! Form::text('ads_left_url', isset($config->ads_left_url) ? $config->ads_left_url : null, array('class'=>'form-control', 'id' => 'ads_left_url', 'placeholder'=>'Ads Left Url', 'value'=>Input::old('ads_left_url'))) !!}
                            </div>
                        </div>
                        <div class="form-group {!! $errors->has('ads_left') ? 'has-error' : '' !!}">
                            <label class="control-label" for="ads_left">Ads Left</label>
                            <div class="controls">
                                <div class="input-group">
                                    @if ($errors->first('ads_left'))
                                        <span class="help-block">{!! $errors->first('ads_left') !!}</span>
                                    @endif
                                    {!! Form::text('ads_left', isset($config->ads_left) ? $config->ads_left : null, array('class'=>'form-control', 'id' => 'ads_left', 'placeholder'=>'Ads Left', 'value'=>Input::old('ads_left'))) !!}
                                    <div class="input-group-btn">
                                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=ads_left')" class="btn btn-danger" type="button">Select</a>
                                    </div>
                                </div>
                                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
                                    @if(isset($config->ads_left))<img src="{{$config->ads_left}}" width="100px" />@endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {!! $errors->has('ads_right_url') ? 'has-error' : '' !!}">
                            <label class="control-label" for="ads_right_url">Ads Right Url</label>
                            <div class="controls">
                                @if ($errors->first('ads_right_url'))
                                    <span class="help-block">{!! $errors->first('ads_right_url') !!}</span>
                                @endif
                                {!! Form::text('ads_right_url', isset($config->ads_right_url) ? $config->ads_right_url : null, array('class'=>'form-control', 'id' => 'ads_right_url', 'placeholder'=>'Ads Right Url', 'value'=>Input::old('ads_right_url'))) !!}
                            </div>
                        </div>
                        <div class="form-group {!! $errors->has('ads_right') ? 'has-error' : '' !!}">
                            <label class="control-label" for="ads_right">Ads Right</label>
                            <div class="controls">
                                <div class="input-group">
                                    @if ($errors->first('ads_right'))
                                        <span class="help-block">{!! $errors->first('ads_right') !!}</span>
                                    @endif
                                    {!! Form::text('ads_right', isset($config->ads_right) ? $config->ads_right : null, array('class'=>'form-control', 'id' => 'ads_right', 'placeholder'=>'Ads Right', 'value'=>Input::old('favicon'))) !!}
                                    <div class="input-group-btn">
                                        <a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=ads_right')" class="btn btn-danger" type="button">Select</a>
                                    </div>
                                </div>
                                <div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
                                    @if(isset($config->ads_right))<img src="{{$config->ads_right}}" style="width: 100px" />@endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <label class="control-label" for="blog_content">Option</label>
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
                        {{ Form::checkbox('active',Input::old('active', 1), $domain->active == 1 ? true : false) }} Active
                    </div>
                </div>
                <div class="form-group">
                    <div class="">
                        {{ Form::checkbox('highlights_number',Input::old('highlights_number', 1),
                        $domain->highlights_number == 1 ? true : false) }} Nổi bật số đầu tiên
                    </div>
                </div>
				<div class="form-group {!! $errors->has('hotline') ? 'has-error' : '' !!}">
                    <label class="control-label" for="hotline">Hotline (Cấu trúc: Hotline|Ten)</label>
                    <div class="controls">
                        @if ($errors->first('hotline'))
                            <span class="help-block">{!! $errors->first('hotline') !!}</span>
                        @endif
                        {!! Form::textarea('hotline', isset($domain->hotline) ? $domain->hotline : null, array('class'=>'form-control', 'rows' => 3, 'id' => 'hotline', 'placeholder'=>'Hotline|Ten', 'value'=>Input::old('hotline'))) !!}
                    </div>
                </div>
                <div class="form-group {!! $errors->has('template') ? 'has-error' : '' !!}">
                    <label class="control-label" for="template">Template</label>
                    <div class="controls">
                        {!! Form::select('template', $template, $domain->template, array('class'=>'form-control', 'id' => 'template')) !!}
                        @if ($errors->first('template'))
                            <span class="help-block">{!! $errors->first('template') !!}</span>
                        @endif
                    </div>
                </div>
                <div class="form-group {!! $errors->has('main_color') ? 'has-error' : '' !!}">
                    <label class="control-label" for="detail">Main Color</label>
                    <div class="controls">
                        {!! Form::text('main_color', isset($config->main_color) ? $config->main_color : null, array('class'=>'form-control jscolor {required:false}', 'id' => 'main_color', 'placeholder'=>'Color', 'value'=>Input::old('main_color'))) !!}
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
                                {!! Form::number('hotline_open', isset($config->hotline_open) ? $config->hotline_open: null, array('min' => 0, 'max' => 24, 'class'=>'form-control', 'id' => 'hotline_open', 'placeholder'=>'Open', 'value'=>Input::old('hotline_open'))) !!}
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
                                {!! Form::number('hotline_close', isset($config->hotline_close) ? $config->hotline_close : null, array('min' => 0, 'max' => 24, 'class'=>'form-control', 'id' => 'hotline_close', 'placeholder'=>'Close', 'value'=>Input::old('hotline_close'))) !!}
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
							{!! Form::text('condau', isset($config->condau)?$config->condau:null, array('class'=>'form-control', 'id' => 'condau', 'placeholder'=>'Image', 'value'=>Input::old('condau'))) !!}
							<div class="input-group-btn">
								<a href="javascript:open_popup('/filemanager/dialog.php?type=1&popup=1&field_id=condau')" class="btn btn-danger" type="button">Select</a>
							</div>
						</div>
						<div class="preview-image" style="background: aliceblue;display: inline-block; width: 100%; padding: 15px; text-align: center;">
						@if(isset($config->condau))<img src="{{$config->condau}}" style="width: 100px" />@endif
						</div>
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
    {!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
    {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
    {!! Form::close() !!}
@stop
