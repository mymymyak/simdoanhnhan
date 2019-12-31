<table class="table table-striped table-bordered table-hover table-success">
    <thead>
        <tr>
            <th>id</th>
            <th>Page title</th>
            <th>Page Slug</th>
            <th>Page Created</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
    @if($pages->count())
        @foreach ($pages as $page)
        <tr>
            <td>{{ $page->id }}</td>
            <td><a>{{ $page->title }}</a></td>
            <td>{{ $page->slug }}</td>
            <td>{{ $page->created_at }}</td>
            <td>
                <a href="{{route('pages.show', ['id' => $page->id])}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                <a href="{{route('pages.edit', ['id' => $page->id])}}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                {!! Form::open(['route' => ['pages.destroy', $page->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
                <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach
    @else
        <tr>
            <td colspan="   5">No results found</td>
        </tr>
    @endif
    </tbody>
</table>
<div class="pull-left">
    <ul class="pagination">
        {!! $pages->render() !!}
    </ul>
</div>