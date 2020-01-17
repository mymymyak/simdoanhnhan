@extends('protected.admin.master')

@section('title', 'Create Domain')

@section('content')

    {!! Form::open(['route' => 'promotion.store']) !!}
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group {!! $errors->has('store_id') ? 'has-error' : '' !!}">
                <label class="control-label" for="store_id">Mã kho</label>
                <div class="controls">
                    {!! Form::text('store_id', null, array('class'=>'form-control', 'id' => 'store_id', 'placeholder'=>'Mã kho khuyến mãi', 'value'=>Input::old('store_id'))) !!}
                    @if ($errors->first('store_id'))
                        <span class="help-block">{!! $errors->first('store_id') !!}</span>
                    @endif
                    <span class="help-inline">Ex: 3329</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group {!! $errors->has('content') ? 'has-error' : '' !!}">
                <label class="control-label" for="store_id">Nội dung khuyến mãi</label>
                <div class="controls">
                    {!! Form::textarea('content', null, array('class'=>'form-control', 'id' => 'content', 'placeholder'=>'Nội dung khuyến mãi', 'value'=>Input::old('content'))) !!}
                    @if ($errors->first('store_id'))
                        <span class="help-block">{!! $errors->first('store_id') !!}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2">
            <div class="form-group {!! $errors->has('status') ? 'has-error' : '' !!}">
                <label class="control-label" for="store_id">Trạng thái</label>
                <div class="controls">
                    {!! Form::select('status', \App\Models\TablePromotion::STATUS_LIST,null,
                    array('class' => 'form-control select-status'))  !!}
                    @if ($errors->first('status'))
                        <span class="help-block">{!! $errors->first('status') !!}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row active-date-row">
        <div class="col-sm-2">
            <div class="form-group {!! $errors->has('active_at') ? 'has-error' : '' !!}">
                <label class="control-label" for="expired_at">Ngày bắt đầu</label>
                <div class="controls">
                    <div class="input-group date">
                        {!! Form::text('active_at', null, array('class'=>'form-control datepicker', 'id' => 'active_at' , 'value'=>Input::old('active_at'))) !!}
                        <div class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </div>
                    </div>
                    @if ($errors->first('active_at'))
                        <span class="help-block">{!! $errors->first('active_at') !!}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row expire-date-row">
        <div class="col-sm-2">
            <div class="form-group {!! $errors->has('expired_at') ? 'has-error' : '' !!}">
                <label class="control-label" for="expired_at">Ngày hết hạn</label>
                <div class="controls">
                    <div class="input-group date">
                        {!! Form::text('expired_at', null, array('class'=>'form-control datepicker', 'id' => 'expired_at' , 'value'=>Input::old('expired_at'))) !!}
                        <div class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </div>
                    </div>
                    @if ($errors->first('expired_at'))
                        <span class="help-block">{!! $errors->first('expired_at') !!}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    {!! Form::submit('Create', array('class' => 'btn btn-success')) !!}
    {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
    {!! Form::close() !!}
@stop
