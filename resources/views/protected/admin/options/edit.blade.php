@extends('protected.admin.master')

@section('title', 'Create New')

@section('content')
    <script type="text/javascript">var ajaxSeoDetail = '<?= route('admin.seo.config.ajax.detail', [],false) ?>';</script>
    {!! Form::open(['route' => 'seo-config.store']) !!}
    <!-- Title -->
    <div class="row">
        <div class="col-xs-12">
            <h2>EDIT - {{$options->option_name}} ({{$options->id}})</h2>
        </div>
        <div class="col-lg-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Controls</h3>
                </div>
                <div class="box-body">
                    @if ($type == 0)
                        <div class="form-group">
                            <label class="control-label" for="news_name">Chọn danh mục</label>
                            <div class="controls" style="position:relative">
								<div class="loading_get" style="position: absolute;display:none;
    right: 0;
    color: red;
    font-size: 16px;
    top: -25px;"><i class="fa fa-spinner fa-spin"></i></div>
                                {!! Form::select('option_name', $loaisim, $options->option_name, ['class'=>'form-control']) !!}
                            </div>
                        </div>
                    @else
                        <div class="form-group">
                            <label class="control-label" for="news_name">Keyword (Đuôi số ex(*564), Đầu số ex(09*))</label>
                            <div class="controls" style="position:relative">
								<div class="loading_get" style="position: absolute;display:none;
    right: 0;
    color: red;
    font-size: 16px;
    top: -25px;"><i class="fa fa-spinner fa-spin"></i></div>
                                {!! Form::text('option_name', $options->option_name, array('class'=>'form-control', 'id' => 'option_name', 'placeholder'=>'Keyword', 'value'=>Input::old('option_name'))) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Controls</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label" for="news_name">Title</label>
                        <div class="controls">
                            {!! Form::text('title', $dataJson->title, array('class'=>'form-control', 'id' => 'title', 'placeholder'=>'Title', 'value'=>Input::old('title'))) !!}
                            @if ($errors->first('title'))
                                <span class="help-block">{!! $errors->first('title') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="news_name">H1</label>
                        <div class="controls">
                            {!! Form::text('h1', $dataJson->h1, array('class'=>'form-control', 'id' => 'h1', 'placeholder'=>'H1', 'value'=>Input::old('h1'))) !!}
                            @if ($errors->first('h1'))
                                <span class="help-block">{!! $errors->first('h1') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="news_name">Mô tả ngắn</label>
                        <div class="controls">
                            {!! Form::textarea('description', $dataJson->description, array('rows' => 5,'class'=>'form-control', 'id' => 'description', 'placeholder'=>'Mô tả ngắn', 'value'=>Input::old('description'))) !!}
                            @if ($errors->first('description'))
                                <span class="help-block">{!! $errors->first('description') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="news_name">Onpage Head</label>
                        <div class="controls">
                            {!! Form::textarea('head', $dataJson->head, array('class'=>'form-control tinymce', 'id' => 'onpagehead', 'placeholder'=>'Mô tả ngắn', 'value'=>Input::old('head'))) !!}
                            @if ($errors->first('head'))
                                <span class="help-block">{!! $errors->first('head') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="news_name">Onpage Foot</label>
                        <div class="controls">
                            {!! Form::textarea('foot', $dataJson->foot, array('class'=>'form-control tinymce', 'id' => 'onpagefoot', 'placeholder'=>'Mô tả ngắn', 'value'=>Input::old('foot'))) !!}
                            @if ($errors->first('foot'))
                                <span class="help-block">{!! $errors->first('foot') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="news_name">Sim gợi ý</label>
                        <div class="controls">
                            {!! Form::textarea('sim_goi_y', $dataJson->sim_goi_y, array('class'=>'form-control', 'id' => 'sim_goi_y', 'placeholder'=>'Sim gợi ý', 'value'=>Input::old('sim_goi_y'))) !!}
                            @if ($errors->first('sim_goi_y'))
                                <span class="help-block">{!! $errors->first('sim_goi_y') !!}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
							<input type="hidden" name="id" value="{{ isset($options->id) ? $options->id : '' }}" />
							<input type="hidden" name="type" value="{{ $type }}" />
                            {!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
                            {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}
    </div>
@stop