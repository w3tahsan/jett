@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Role Manager</a></li>
    </ol>
</div>
<div class="row">
    <div class="col-lg-9">
        @can('show_role')
        <div class="card">
            <div class="card-header">
                <h3>Role List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>SL</th>
                        <th>Role</th>
                        <th>Permission</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($roles as $sl=>$role)
                    <tr>
                        <td>{{ $sl+1 }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach ($role->getAllPermissions() as $permission)
                                <span class="badge badge-success my-2">{{ $permission->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                    <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="5" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="19" cy="12" r="2"></circle></g></svg>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('role.edit', $role->id) }}">Edit</a>
                                    <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        @endcan
        <div class="card">
            <div class="card-header">
                <h3>User List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>
                            @forelse ($user->getRoleNames() as $role)
                                <span class="badge badge-success">{{ $role }}</span>
                            @empty
                                Not Assigned Yet
                            @endforelse
                        </td>
                        <td>
                            <a href="{{ route('remove.user.role', $user->id) }}" class="btn btn-danger">Remove</a>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        {{-- <div class="card">
            <div class="card-header">
                <h3>Add New Permission</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('add.permission') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Permission Name</label>
                        <input type="text" class="form-control" name="permission">
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Permission</button>
                    </div>
                </form>
            </div>
        </div> --}}
        @can('add_role')
        <div class="card">
            <div class="card-header">
                <h3>Add New Role</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('add.role') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Role Name</label>
                        <input type="text" class="form-control" name="role_name">
                    </div>
                    <div class="mb-3">
                        <h5>Select Permission</h5>
                        <div class="form-group">
                            @foreach ($permissions as $permission)
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" name="permission[]" class="form-check-input" value="{{ $permission->id }}">{{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Add Role</button>
                    </div>
                </form>
            </div>
        </div>
        @endcan
        <div class="card">
            <div class="card-header">
                <h3>Assign Role</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('assign.role') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <select name="user_id" id="" class="form-control">
                            <option value=""> Select User </option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <select name="role_id" id="" class="form-control">
                            <option value=""> Select Role </option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Assign Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
