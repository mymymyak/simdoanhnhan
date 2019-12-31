<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">List Domain</h3>
    </div>
    <div class="box-body">
        <div class="pull-right"><a href="{{route('domain.create')}}" class="btn btn-success">Add</a></div>
        <table class="table table-striped table-bordered table-hover table-success">
            <thead>
            <tr>
                <th>id</th>
                <th>Domain</th>
                <th>Created</th>
                <th>Status</th>
                <th>Template</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($domains->total())
                @foreach ($domains as $domain)
                    @php
                    $class="btn btn-danger btn-xs";
                    $text = 'Disable';
                    if ($domain->active == 1) {
                        $class="btn btn-success btn-xs";
                        $text = 'Enable';
                    }
                    @endphp
                    <tr>
                        <td>{{ $domain->id }}</td>
                        <td>{{ $domain->domain }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($domain->created_at)) }}</td>
                        <td><a href="javascript:void(0)" class="{{$class}}">{{$text}}</a></td>
                        <td>{{ !empty($domain->template) ? $domain->template : 'default' }}</td>
                        <td>
                            <a href="{{route('domain.show', ['id' => $domain->id])}}" class="btn btn-xs btn-primary"><i
                                        class="fa fa-eye"></i></a>
                            <a href="{{route('domain.edit', ['id' => $domain->id])}}" class="btn btn-xs btn-warning"><i
                                        class="fa fa-edit"></i></a>
                            @if (isAdmin()) {!! Form::open(['route' => ['domain.destroy', $domain->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
                            <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                            {!! Form::close() !!}
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4">No results found</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <div class="box-footer clearfix">
        <ul class="pagination pagination-sm no-margin pull-right">
            {!! $domains->links() !!}
        </ul>
    </div>
</div>
