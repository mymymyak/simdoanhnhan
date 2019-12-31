@if($roles->total())
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List Roles</h3>
        </div>
        <div class="box-body">
            <table class="table table-striped table-bordered table-hover table-success">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Role name</th>
                    <th>Role slug</th>
                    <th>Role permissable</th>
                    <th>Action</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td><a href="{{route('role.edit', ['id' => $role->id])}}">{{ $role->name }}</a></td>
                        <td>{{ $role->slug }}</td>
                        <td>{{ !empty($role->permissions) ? json_encode($role->permissions): '' }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                            <a href="{{route('role.edit', ['id' => $role->id])}}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
            @else
                <div class="alert alert-danger">No results found</div>
            @endif
        </div>
        <div class="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                {!! $roles->links() !!}
            </ul>
        </div>
    </div>