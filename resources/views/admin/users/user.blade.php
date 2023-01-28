@extends('layouts.dashboard')


@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">User List</a></li>
    </ol>
</div>
@can('show_user_list')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9 m-auto">
            <div class="card">
                <div class="card-header d-flex">
                    <h3>User List </h3>
                    <span  class="">Total User: {{$total_user}}</span>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>SL</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>

                        @foreach ($users as $key=>$user)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                @if($user->image == null)
                                    <img width="50" src="{{ Avatar::create($user->name)->toBase64() }}" />
                                @else
                                    <img src="{{asset('dashboard_asset/images/profile/17.jpg')}}" width="20" alt=""/>
                                @endif
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->created_at->diffForHumans()}}</td>
                            <td>
                                @can('delete_user')
                                <a href="{{route('delete', $user->id)}}" class="btn btn-danger">Delete</a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h3>Add New User</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.register') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Name</label>
                            <input type="text"  name="name" class="form-control">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Confirm Password</label>
                            <input type="password"  name="password_confirmation" class="form-control">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Add user</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection
