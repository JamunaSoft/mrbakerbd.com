@extends('frontend.index')
@section('seo_title', 'Search Products | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_robots', 'noindex, follow')
@section('content')
    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="{{ optimized_asset('frontend/images/breadcrumbs-bg.jpg') }}">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">Search Results</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li class="active">Search</li>
                </ul>
            </div>
        </div>
    </section>
    <section class="section section-xxl bg-default text-md-start">
        <div class="container">
            <div class="row row-50">
                <div class="col-12">
                    <div class="product-top-panel group-md mb-4">
                        <p class="product-top-panel-title">
                            @if(request('keyword'))
                                Search results for "<strong>{{ request('keyword') }}</strong>":
                            @else
                                Search Results:
                            @endif
                            {{ $products->total() }} found
                        </p>
                    </div>
                    <div class="row row-30 row-lg-50">
                        @forelse($products as $product)
                            <div class="col-sm-6 col-md-4 col-lg-6 col-xl-4">
                                <article class="product" style="min-height: 380px;">
                                    <div class="product-body">
                                        <a href="{{ route('product', $product->slug) }}" class="product-figure-link">
                                            <div class="product-figure">
                                                @if ($product->image && file_exists(public_path($product->image->path . '/' . $product->image->name)))
                                                    <img src="{{ file_exists(public_path($product->image->path . '/thumbs/' . $product->image->name)) ? $product->image->optimized_thumbnail_url : $product->image->optimized_url }}" loading="lazy" decoding="async" width="300" height="200" style="width: 300px; height: 200px;" alt="{{ $product->name }}">
                                                @else
                                                    <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" style="width: 300px; height: 200px;" width="300" height="200">
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
                                            <div class="product-button" style="margin-top: -27px;!important">
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
                                <p>No products found for your search.</p>
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
