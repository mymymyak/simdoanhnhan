@extends('protected.admin.master')

@section('title', 'Custom Query')

@section('content')
    <h2>Custom query configuration</h2>
    {!! Form::open(['route' => 'managers.custom.query.configuration.store']) !!}
    <!-- Title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group {!! $errors->has('customQueryConfiguration') ? 'has-error' : '' !!}">
                <label class="control-label" for="customQueryConfiguration">Cấu hình custom query (ten_query|anmakho1,anmakho2 ..) hoặc (global|anmakho1,anmakho2 ...) để ẩn trên toàn bộ website</label>
                <div class="controls">
                    @if ($errors->first('customQueryConfiguration'))
                        <span class="help-block">{!! $errors->first('customQueryConfiguration') !!}</span>
                    @endif
                    {!! Form::textarea('customQueryConfiguration',
                    $customQueryConfiguration, array('class'=>'form-control', 'rows' =>30, 'id' => 'customQuery',
                    'placeholder'=>'Custom query configuration', 'value'=>Input::old('customQueryConfiguration'))) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    {!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
    {!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
    {!! Form::close() !!}
@stop
