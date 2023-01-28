@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Category</a></li>
    </ol>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Category List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>SL</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Added By</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($categories as $key=>$category)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td><img width="100" src="{{ asset('uploads/category') }}/{{ $category->category_image }}" alt=""></td>
                            <td>{{ $category->rel_to_user->name }}</td>
                            <td>{{ $category->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        @can('edit_category')
                                        <a class="dropdown-item" href="{{ route('category.edit', $category->id) }}">Edit</a>
                                        @endcan
                                        @can('delete_category')
                                        <a class="dropdown-item" href="{{ route('category.delete', $category->id) }}">Delete</a>
                                        @endcan
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Trash Category List</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>SL</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Added By</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($trashed as $key=>$trash)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $trash->category_name }}</td>
                            <td><img width="100" src="{{ asset('uploads/category') }}/{{ $trash->category_image }}" alt=""></td>
                            <td>{{ $trash->rel_to_user->name }}</td>
                            <td>{{ $trash->created_at->diffForHumans() }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-success light sharp" data-toggle="dropdown">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('category.restore', $trash->id) }}">restore</a>
                                        <a class="dropdown-item" href="{{ route('category.force.delete', $trash->id) }}">Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @can('add_category')
            <div class="card">
                <div class="card-header">
                    <h3>Add Category</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('category.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category_name">
                            @error('category_name')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Category Image</label>
                            <input type="file" class="form-control" name="category_image" onchange="document.getElementById('category_image').src = window.URL.createObjectURL(this.files[0])">
                            <br>
                            <img width="200" id="category_image" src="" alt="">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection
