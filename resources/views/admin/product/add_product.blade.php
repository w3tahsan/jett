@extends('layouts.dashboard')

@section('content')
    <div class="page-titles">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
            <li class="breadcrumb-item"><a href="javascript:void(0)">Product</a></li>
        </ol>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @can('add_product')
                <div class="card">
                    <div class="card-header">
                        <h3>Add Product</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <select name="category_id" class="form-control category_id">
                                        <option value="">-- Select Category --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <select name="subcategory_id" id="subcategory" class="form-control">
                                        <option value="">-- Select Sub Category --</option>
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <input type="text" name="product_name" class="form-control"
                                            placeholder="Product Name">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <input type="number" name="price" class="form-control" placeholder="Product Price">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <input type="number" name="discount" class="form-control" placeholder="Discount %">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <select name="brand_name" class="form-control">
                                            <option value="">-- Select Brand --</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mt-3">
                                        <input type="text" name="short_desp" class="form-control"
                                            placeholder="Short Description">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mt-3">
                                        <textarea name="long_desp" id="summernote" class="form-control" placeholder="Long Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <label for="" class="form-label">Product Preview</label>
                                        <input type="file" name="preview" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mt-3">
                                        <label for="" class="form-label">Product Thumbnails</label>
                                        <input type="file" name="thumbnails[]" multiple class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">Add Product</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endcan
        </div>
    </div>
@endsection

@section('footer_script')
    <script>
        $('.category_id').change(function() {
            var category_id = $(this).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/getSubcategory',
                data: {
                    'category_id': category_id
                },
                success: function(data) {
                    $('#subcategory').html(data);
                }
            })

        })
    </script>
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    </script>
@endsection
