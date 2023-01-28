@extends('layouts.dashboard')
@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Add Brand</a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3>Brands</h3>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Action</th>
                        </tr>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand->brand_name }}</td>
                                <td><img width="70" src="{{ asset('uploads/brand') }}/{{ $brand->brand_logo }}"
                                        alt=""></td>
                                <td>
                                    <a href="" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3>Add Brand</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('add.brand') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="text" placeholder="Brand name" name="brand_name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="file" name="brand_logo" class="form-control">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Add Brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
