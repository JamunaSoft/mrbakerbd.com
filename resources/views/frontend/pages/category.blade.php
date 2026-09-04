@extends('frontend.index')
@section('seo_title', $category->name . ' | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_description', $category->meta_description ?: ($category->description ?: 'Explore fresh ' . $category->name . ' from Mr. Baker.'))
@section('seo_keywords', $category->meta_keywords)
@section('page-styles')
    <style>
        @media (max-width: 576px) {
            .img-responsive {
                width: 100%!important;
                height: auto!important;
            }
            .product-figure{
                min-height: 130px!important;
            }
            .product{
                min-height: 130px!important;
                height: 100%!important;
            }
            .product-title{
                font-size: 11px!important;
            }
            .product-price{
                font-size: 11px!important;
            }
            .product-button .button {
                width: 35px;
                height: 35px;
                font-size: 10px;
                line-height: 32px;
            }
        }
    </style>

@stop
@section('content')
    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="{{ $category->bannerImage ? $category->bannerImage->optimized_url : optimized_asset('frontend/images/breadcrumbs-bg.jpg') }}">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">{{ $category->name }}</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li class="active">{{ $category->name }}</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="section section-xxl bg-default text-md-start">
        <div class="container">
            <div class="row row-50">
                <div class="col-lg-4 col-xl-3">
                    <div class="aside row row-30 row-md-50 justify-content-md-between">
                        <div class="aside-item col-sm-6 col-md-5 col-lg-12 d-none d-md-block" style="margin-bottom:30px;">
                            <h6 class="aside-title">Categories</h6>
                            <ul class="list-group list-group-flush">
                                @foreach($categoriesList as $cat)
                                    <li class="list-group-item p-0 border-0" style="background: none;">
                                        <a href="{{ route('category', $cat->slug) }}"
                                           class="d-block py-2 px-3 @if($cat->id == $category->id) fw-bold active @endif"
                                           style="text-decoration: none; @if($cat->id == $category->id) background-color: #2f3194; color: #fff; font-weight:bold; @endif">
                                            {{ $cat->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <form action="{{ route('shop') }}" method="GET">
                            {{-- Price Filter --}}
                            <div class="aside-item col-12">
                                <h6 class="aside-title">Filter by Price</h6>
                                <div class="rd-range"
                                     data-min="0"
                                     data-max="5000"
                                     data-min-diff="100"
                                     data-start="[{{ request('min_price', 40) }}, {{ request('max_price', 5000) }}]"
                                     data-step="1"
                                     data-tooltip="false"
                                     data-input=".rd-range-input-value-1"
                                     data-input-2=".rd-range-input-value-2">
                                </div>
                                <div class="group-xs group-justify">
                                    {{-- <div>
                                         <button class="button button-sm button-secondary button-zakaria" type="submit">Filter</button>
                                     </div>--}}
                                    <div>
                                        <div class="rd-range-wrap">
                                            <div class="rd-range-title">Price:</div>
                                            <div class="rd-range-form-wrap">
                                                <input class="rd-range-input rd-range-input-value-1"
                                                       style="max-width: 20px !important"
                                                       type="text"
                                                       name="min_price"
                                                       value="{{ request('min_price', 40) }}"><span>Tk</span>
                                            </div>
                                            <div class="rd-range-divider"></div>
                                            <div class="rd-range-form-wrap">
                                                <input class="rd-range-input rd-range-input-value-2"
                                                       type="text"
                                                       style="max-width: 40px !important"
                                                       name="max_price"
                                                       value="{{ request('max_price', 5000) }}"><span>Tk</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button (if not already present) --}}
                            <button type="submit" class="button button-sm button-secondary button-zakaria mt-3">Apply Filters</button>
                        </form>

                        <!-- Popular Products Sidebar -->
                        <div class="aside-item col-sm-6 col-lg-12  d-none d-md-block">
                            <h6 class="aside-title">Popular products</h6>
                            <div class="row row-20 gutters-10">
                                @foreach($popularProducts->take(5) as $popular)
                                    <div class="col-4 col-sm-6 col-md-12">
                                        <article class="product-minimal">
                                            <div class="unit unit-spacing-sm flex-column flex-md-row align-items-center">
                                                <div class="unit-left">
                                                    <a class="product-minimal-figure" href="{{ route('product', $popular->slug) }}">
                                                        @if ($popular->image && file_exists(public_path($popular->image->path . '/' . $popular->image->name)))
                                                            <img src="{{ file_exists(public_path($popular->image->path . '/thumbs/' . $popular->image->name)) ? $popular->image->optimized_thumbnail_url : $popular->image->optimized_url }}" loading="lazy" decoding="async" style="width: 70px; height: 50px;" alt="{{ $popular->name }}">
                                                        @else
                                                            <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" style="width: 70px; height: 50px;">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="unit-body">
                                                    <p class="product-minimal-title">
                                                        <a href="{{ route('product', $popular->slug) }}">{{ $popular->name }}</a>
                                                    </p>
                                                    @if($popular->type == 1)
                                                        <p class="product-minimal-price">{{ $popular->regular_price }} Tk</p>
                                                    @elseif($popular->type == 2 && $popular->details)
                                                        <p class="product-minimal-price">
                                                            {{ $popular->details->min('regular_price') }} Tk
                                                            &ndash;
                                                            {{ $popular->details->max('regular_price') }}  Tk
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="product-top-panel group-md">
                        <p class="product-top-panel-title">
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }} Products
                        </p>
                    </div>
                    <div class="row row-30 row-lg-50">
                        @forelse($products as $product)
                            <div class="col-6 col-sm-6 col-md-4 col-lg-6 col-xl-4">
                                <article class="product"   style="min-height: 350px;">
                                    <div class="product-body">
                                        <a class="product-figure" href="{{ route('product', $product->slug) }}">
                                        <div class="product-figure">
                                                @if ($product->image && file_exists(public_path($product->image->path . '/' . $product->image->name)))
                                                <img src="{{ file_exists(public_path($product->image->path . '/thumbs/' . $product->image->name)) ? $product->image->optimized_thumbnail_url : $product->image->optimized_url }}" loading="lazy" decoding="async" class="img-responsive" style="width: 300px; height: 170px;" width="300" height="170" alt="{{ $product->name }}">
                                            @else
                                                <img src="{{ asset('images/products/placeholder.png') }}"  class="img-responsive" style="width: 300px; height: 170px;" alt="Image" width="300" height="170">
                                            @endif
                                        </div>
                                        </a>
                                        <h5 class="product-title"><a href="{{ route('product', $product->slug) }}">{{ $product->name }}</a></h5>
                                        <div class="product-price-wrap">
                                            @if($product->type == 1)
                                                <div class="product-price"> {{ $product->regular_price }} Tk</div>
                                            @endif
                                            @if($product->type == 2)
                                                <div class="product-price">
                                                    {{ $product->details->min('regular_price') }} Tk
                                                    &ndash;
                                                    {{ $product->details->max('regular_price') }} Tk
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($product->discount_price)
                                        <span class="product-badge product-badge-sale">Sale</span>
                                    @endif
                                    <div class="product-button-wrap">
                                        <div class="product-button"><a class="button button-primary-2 button-zakaria fl-bigmug-line-search74" href="{{ route('product', $product->slug) }}"></a></div>
                                        @if($product->type == 1)
                                            <div class="product-button"   style="margin-top: -30px;!important">
                                                <form method="POST" action="{{ route('cart.add') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $product->id }}">
                                                    <input type="hidden" name="name" value="{{ $product->name }}">
                                                    <input type="hidden" name="price" value="{{ $product->regular_price }}">
                                                    <input type="hidden" name="slug" value="{{ $product->slug }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <input type="hidden" name="image" value="{{ $product->image->thumbnail_url ?? asset('images/products/placeholder.png') }}">
                                                    <button type="submit" class="button button-primary-2 button-zakaria fl-bigmug-line-shopping202"></button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>No products found in this category.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="pagination-wrap mt-10">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
