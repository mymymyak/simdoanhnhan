@extends('protected.admin.master')

@section('title', 'Custom Query')

@section('content')

<h2>Site Setting</h2>
{!! Form::open(['route' => 'managers.site.custom.query.store']) !!}
<!-- Title -->
<div class="row">
    <div class="col-lg-12">
        <div class="form-group {!! $errors->has('customQuery') ? 'has-error' : '' !!}">
            <label class="control-label" for="customQuery">Custom query</label>
            <div class="controls">
                @if ($errors->first('customQuery'))
                    <span class="help-block">{!! $errors->first('customQuery') !!}</span>
                @endif
                {!! Form::textarea('customQuery', $customQuery, array('class'=>'form-control', 'rows' =>30, 'id' => 'customQuery', 'placeholder'=>'Custom query', 'value'=>Input::old('customQuery'))) !!}
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
{!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
{!! link_to(URL::previous(), 'Cancel', ['class' => 'btn btn-default']) !!}
{!! Form::close() !!}
@stop