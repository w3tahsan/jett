@extends('layouts.dashboard')
@section('content')
<div class="row">
    <div class="col-lg-8">
       <div class="card">
            <div class="card-header">
                <h3>Edit Role</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('update.role') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Role Name</label>
                       <input type="text" readonly class="form-control" value="{{ $role->name }}">
                       <input type="hidden" readonly class="form-control" name="role_id" value="{{ $role->id }}">
                    </div>
                    <div class="mb-3">
                        <h5>Select Permission</h5>
                        <div class="form-group">
                            @foreach ($permissions as $permission)
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input type="checkbox" {{($role->hasPermissionTo($permission->name))?'checked':''}} name="permission[]" class="form-check-input" value="{{ $permission->id }}">{{ $permission->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
