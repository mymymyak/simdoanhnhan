@if($blogs->count())
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>id</th>
            <th>Blog title</th>
            <th>Blog des</th>
            <th>Blog Img</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($blogs as $blog)
        <tr>
            <td>{{ $blog->blog_id }}</td>
            <td><a>{{ $blog->blog_title }}</a></td>
            <td>{{ $blog->blog_des }}</td>
            <td>{{ $blog->blog_content }}</td>
            <td>
                <a href="{{route('blog.show', ['id' => $blog->blog_id])}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                <a href="{{route('blog.edit', ['id' => $blog->blog_id])}}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                {!! Form::open(['route' => ['blog.destroy', $blog->blog_id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
                <button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
                {!! Form::close() !!}
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
@else
<div class="alert alert-danger">No results found</div>
@endif
<div class="pull-left">
    <ul class="pagination">
        {!! $blogs->render() !!}
    </ul>
</div>