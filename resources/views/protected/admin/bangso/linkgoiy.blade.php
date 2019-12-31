@extends('protected.admin.master')

@section('title', 'Link gợi ý')

@section('content')

<h2>Link gợi ý</h2>
{!! Form::open(['route' => 'managers.linkgoiy.save']) !!}
<div class="row">
    <div class="col-lg-9">
        <div class="form-group {!! $errors->has('linkgoiy') ? 'has-error' : '' !!}">
            <label class="control-label" for="linkgoiy">Link Gợi ý</label>
            <div class="controls">
                {!! Form::textarea('linkgoiy', $listItem, array('class'=>'form-control', 'id' => 'linkgoiy', 'placeholder'=>'linkgoiy', 'value'=>Input::old('linkgoiy'))) !!}
                @if ($errors->first('linkgoiy'))
                    <span class="help-block">{!! $errors->first('linkgoiy') !!}</span>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
{!! Form::submit('Save', array('class' => 'btn btn-success')) !!}
{!! Form::close() !!}
@stop