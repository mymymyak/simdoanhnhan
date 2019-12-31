@extends('protected.admin.master')

@section('title', 'List Users')

@section('content')

<h2>Registered Users</h2>
    <p class="pull-left">Here you would normally search for users but since this is just a demo, I'm listing all of them.</p>
    <a class="btn btn-success pull-right" href="{{ route('profiles.create') }}">Add</a>
    <table class="table table-striped table-bordered table-hover table-success">
        <thead>
            <tr>
              <th>id</th>
              <th>Email</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Domain</th>
              <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="profiles/{{ $user->id }}">{{ $user->email }}</a> <br>
                @if ($user->inRole($admin))
                <span class="label label-success">{{ 'Admin' }}</span>
                @endif
                </td>
                <td>{{ $user->first_name}}</td>
                <td>{{ $user->last_name}}</td>
                <td>{{ $user->domain}}</td>
				<td>
					<a href="{{route('profiles.edit', ['id' => $user->id])}}" class="btn btn-xs btn-warning"><i
								class="fa fa-edit"></i></a>
					{!! Form::open(['route' => ['profiles.destroy', $user->id], 'method' => 'DELETE', 'style' => 'display: inline', 'onsubmit' =>'return confirm(\'Are you sure you want delete record?\')']) !!}
					<button class="btn btn-xs btn-danger"><i class="fa fa-remove"></i></button>
					{!! Form::close() !!}
				</td>
             </tr>
            @endforeach

        </tbody>
    </table>

@stop
