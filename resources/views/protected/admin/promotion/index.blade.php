@extends('protected.admin.master')

@section('title', 'Danh sách khuyến mãi')
@section('content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Danh sách khuyến mãi</h3>
        </div>
        <div class="box-body">
            <div class="pull-right"><a href="{{route('promotion.create')}}" class="btn btn-success">
                    <i class="fa fa-plus"></i> Thêm mới</a>
            </div>
            <div class="ajax-content">
                @include('protected/admin/promotion/list')
            </div>
        </div>
    </div>
@stop
