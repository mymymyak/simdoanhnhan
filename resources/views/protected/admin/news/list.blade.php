<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">List News for: <b>{{ session('domain_setting') }}</b></h3>
    </div>
    <div class="box-body">
        <table class="table table-striped table-bordered table-hover table-success">
            <thead>
            <tr>
                <th>id</th>
                <th>News title</th>
                <th>Domain</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($news->total())
                @foreach ($news as $new)
                    <tr>
                        <td>{{ $new->id }}</td>
                        <td><a>{{ $new->title }}</a></td>
                        <td>{!! !empty($new->domain) ? $new->domain : '<font color="red">All Domain</font>' !!}</td>
                        <td>
                            <a href="{{route('news.edit', ['id' => $new->id])}}" class="btn btn-xs btn-warning"><i
                                        class="fa fa-edit"></i></a>
                            {!! Form::open(['route' => ['news.destroy', $new->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
                            <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                            {!! Form::close() !!}
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
            {!! $news->links() !!}
        </ul>
    </div>
</div>