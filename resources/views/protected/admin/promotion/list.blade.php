<table class="table table-striped table-bordered table-hover table-success">
    <thead>
    <tr>
        <th>ID</th>
        <th>Các mã kho</th>
        <th>Trạng thái</th>
        <th>Nội dung</th>
        <th>Ngày tạo</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if($promotions->count())
        @foreach ($promotions as $promotion)
            <tr>
                <td>{{ $promotion->id }}</td>
                <td>{{ $promotion->store_id }}</td>
                <td>{{ $promotion->status }}</td>
                <td>{{ $promotion->content }}</td>
                <td>{{ $promotion->created_at }}</td>
                <td>
                    <a href="{{route('promotion.show', ['id' => $promotion->id])}}" class="btn btn-xs btn-primary"><i class="fa fa-eye"></i></a>
                    <a href="{{route('promotion.edit', ['id' => $promotion->id])}}" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                    {!! Form::open(['route' => ['promotion.destroy', $promotion->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
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
