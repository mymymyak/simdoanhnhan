@extends('protected.admin.master')

@section('title', 'Upload sitemap và robots')

@section('content')
    {!! Form::open(['route' => 'managers.sitemap.robot.doUpload','enctype' => 'multipart/form-data']) !!}
    <div class="row">
        <div class="col-xs-12">
            <h2>Upload sitemap và robots</h2>
            <div class="message"></div>
            <div class="box box-primary">
                <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="control-label" for="sitemap">Sitemap</label>
                            <div class="controls" style="position:relative;">
                                <input type="file" name="sitemap" placeholder="Sitemap" class="form-control"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="robots">Robots</label>
                            <div class="controls" style="position:relative;">
                                <input type="file" name="robots" placeholder="robots" class="form-control"/>
                            </div>
                        </div>
                        {!! Form::submit('Submit', array('class' => 'btn btn-success')) !!}
                    </div>
                </div>
            </div>
        </div>
        <h2></h2>
        <div class="clearfix"></div>

        {!! Form::close() !!}
    </div>
@stop
