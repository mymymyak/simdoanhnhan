<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">List Seo Options for: <b>{{ session('domain_setting') }}</b></h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered table-hover table-success">
            <thead>
            <tr>
                <th>id</th>
                <th>Option Name</th>
                <th>Domain</th>
                <th>Created Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($options->total())
                @foreach ($options as $option)
                    <tr>
                        <td>{{ $option->id }}</td>
                        <td><a>{{ $option->option_name }}</a></td>
                        <td>{{$option->domain}}</td>
                        <td><a>{{ $option->created_at }}</a></td>
                        <td>
                            <a href="{{route('seo-config.show', ['id' => $option->id])}}" class="btn btn-xs btn-primary"><i
                                        class="fa fa-eye"></i></a>
                            <a href="{{route('seo-config.edit', ['id' => $option->id])}}" class="btn btn-xs btn-warning"><i
                                        class="fa fa-edit"></i></a>
                            {!! Form::open(['route' => ['seo-config.destroy', $option->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
                            <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">No results found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
            {!! $options->links() !!}
        </ul>
    </div>
</div>