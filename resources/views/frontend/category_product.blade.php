@extends('frontend.master')
@section('content')
    <!-- ======================= Shop Style 1 ======================== -->
    <section class="bg-cover" style="background:url({{ asset('frontend_asset/img/banner-2.png') }}) no-repeat;">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                    <div class="text-left py-5 mt-3 mb-3">
                        <h1 class="ft-medium mb-3">{{ $categories_info->category_name }}</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ======================= Shop Style 1 ======================== -->

    <div class="container my-5">
        <div class="row align-items-center rows-products">
            @foreach ($categorized_products as $product)
                <!-- Single -->
                <div class="col-xl-3 col-lg-4 col-md-6 col-6">
                    <div class="product_grid card b-0">
                        @if ($product->discount)
                            <div class="badge bg-danger text-white position-absolute ft-regular ab-right text-upper">
                                -{{ $product->discount }}%</div>
                        @endif
                        <div class="card-body p-0">
                            <div class="shop_thumb position-relative">
                                <a class="card-img-top d-block overflow-hidden"
                                    href="{{ route('product.details', $product->slug) }}"><img class="card-img-top"
                                        src="{{ asset('uploads/product/preview') }}/{{ $product->preview }}"
                                        alt="..."></a>
                            </div>
                        </div>
                        <div class="card-footer b-0 p-0 pt-2 bg-white d-flex align-items-start justify-content-between">
                            <div class="text-left">
                                <div class="text-left">
                                    <div class="elso_titl"><span
                                            class="small">{{ $product->rel_to_cat->category_name }}</span>
                                    </div>
                                    <h5 class="fs-md mb-0 lh-1 mb-1"><a
                                            href="{{ route('product.details', $product->slug) }}">{{ $product->product_name }}</a>
                                    </h5>
                                    <div class="star-rating align-items-center d-flex justify-content-left mb-2 p-0">
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star filled"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="elis_rty">
                                        @if ($product->discount)
                                            <span class="ft-medium text-muted line-through fs-md mr-2">TK
                                                {{ $product->price }}</span>
                                        @endif
                                        <span class="ft-bold text-dark fs-sm">TK {{ $product->after_discount }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
