@extends('protected.admin.master')

@section('title', 'Create New')

@section('content')
    <script type="text/javascript">var ajax_bangso_detail = '/admin/ajax-bang-so-danh-muc';</script>
    {!! Form::open(['route' => 'managers.bangso.danhmuc.save', 'id' => 'ajaxForm']) !!}
    <!-- Title -->
    <div class="row">
        <div class="col-xs-12">
            <h2>Bảng số danh mục</h2>
            <div class="message"></div>
        </div>
        <div class="col-lg-3">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Controls</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label" for="danh_muc">Chọn danh mục</label>
                        <div class="controls" style="position:relative;">
                            {!! Form::select('danh_muc', $loaisim, null, ['class'=>'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Controls</h3>
                </div>
                <div class="box-body">
                    <textarea name="bangso" rows="20" placeholder="Bảng sim" class="form-control"></textarea>
                    <!-- Update Profile Field -->
                    <div class="form-group">
                        {!! Form::submit('Save', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        {!! Form::close() !!}
    </div>
@stop