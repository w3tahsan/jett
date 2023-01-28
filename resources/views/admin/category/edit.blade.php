@extends('layouts.dashboard')

@section('content')
<div class="page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Category</a></li>
    </ol>
</div>
<div class="row">
    <div class="col-lg-8 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3>Edit Category</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('category.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category_name" value="{{ $category_info->category_name }}">
                            <input type="hidden" name="category_id" value="{{ $category_info->id }}">
                            @error('category_name')
                                <strong class="text-danger">{{ $message }}</strong>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Category Image</label>
                            <input type="file" class="form-control" name="category_image" onchange="document.getElementById('category_image').src = window.URL.createObjectURL(this.files[0])">
                            <br>
                            <img id="category_image" src="{{ asset('uploads/category') }}/{{ $category_info->category_image}}" alt="">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
@endsection
