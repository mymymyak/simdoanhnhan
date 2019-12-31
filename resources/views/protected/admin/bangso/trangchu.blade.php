@extends('protected.admin.master')

@section('title', 'Bảng số tổng')

@section('content')
    <h1>Bảng số</h1>
    {!! Form::open(['method' => 'POST', 'route' => ['managers.bangso.trang-chu.save']]) !!}
    <div class="row">
        <div class="col-md-9">
            <textarea name="bangso" rows="20" placeholder="Bảng sim" class="form-control">{{!empty(Input::old('bangso')) ? Input::old('bangso') : $bangso}}</textarea>
            <!-- Update Profile Field -->
            <div class="form-group">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}

@endsection