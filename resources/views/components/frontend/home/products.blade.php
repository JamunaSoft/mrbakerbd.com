<section class="section section-xxl pt-0 pb-5" style="background-color: #bed3fe;">
    <div class="container">
        @foreach($categories as $category)
            @if(($category->products->count() > 4) || ($category->child->count() > 4))
                <h2 class="wow fadeScale mb-3" style="padding: 60px 0px 30px 0px; font-weight: 500; font-size: 40px;"> {{ $category->name }} </h2>
                <div class="position-relative" style="padding-right: 50px;">
                    <div class="row row-30 row-lg-50">
                        @php $i = 0; @endphp

                        {{-- Show main category products --}}
                        @foreach($category->lr_products as $cp)
                            @if($i >= 4) @break @endif
                            <div class="col-6 col-sm-6 col-md-4 col-lg-6 col-xl-3">
                                <article class="product" style="min-height: 380px;">
                                    <div class="product-body">
                                        <div class="product-figure">
                                            <a href="{{ route('product', $cp->slug) }}">
                                                @if ($cp->image && file_exists(public_path($cp->image->path . '/thumbs/' . $cp->image->name)))
                                                    <img src="{{ $cp->image->thumbnail_url }}" class="img-responsive" width="300" height="170" style="width: 300px; height: 170px;" alt="{{ $cp->name }}">
                                                @else
                                                    <img src="{{ asset('images/products/placeholder.png') }}"  class="img-responsive" style="width: 300px; height: 170px;" alt="Image" width="300" height="170">
                                                @endif
                                            </a>
                                        </div>
                                        <h5 class="product-title">
                                            <a href="{{ route('product', $cp->slug) }}">{{ $cp->name }}</a>
                                        </h5>
                                        <div class="product-price-wrap">
                                            @if($cp->type == 1)
                                                <div class="product-price">{{ $cp->regular_price }} Tk</div>
                                            @elseif($cp->type == 2)
                                                <div class="product-price">
                                                    {{ $cp->details->min('regular_price') }} Tk – {{ $cp->details->max('regular_price') }} Tk
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-button-wrap">
                                        <div class="product-button">
                                            <a class="button button-primary-2 button-zakaria fl-bigmug-line-search74" href="{{ route('product', $cp->slug) }}"></a>
                                        </div>
                                        @if($cp->type == 1)
                                            <div class="product-button" style="margin-top: -30px;">
                                                <form method="POST" action="{{ route('cart.add') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $cp->id }}">
                                                    <input type="hidden" name="name" value="{{ $cp->name }}">
                                                    <input type="hidden" name="price" value="{{ $cp->regular_price }}">
                                                    <input type="hidden" name="slug" value="{{ $cp->slug }}">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <input type="hidden" name="image" value="{{ $cp->image->thumbnail_url ?? asset('images/products/placeholder.png') }}">
                                                    <button type="submit" class="button button-primary-2 button-zakaria fl-bigmug-line-shopping202"></button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </article>
                            </div>
                            @php $i++; @endphp
                        @endforeach

                        {{-- Show child category products --}}
                        @foreach($category->child as $cc)
                            @foreach($cc->lr_products as $cp)
                                @if($i >= 4) @break 2 @endif
                                <div class="col-6 col-sm-6 col-md-4 col-lg-6 col-xl-3">
                                    <article class="product">
                                        <div class="product-body">
                                            <div class="product-figure">
                                                <a href="{{ route('product', $cp->slug) }}">
                                                    <img src="{{ asset('images/products/thumb/' . $cp->image) }}"  class="img-responsive" alt="{{ $cp->name }}" width="187" height="155"/>
                                                </a>
                                            </div>
                                            <h5 class="product-title">
                                                <a href="{{ route('product', $cp->slug) }}">{{ $cp->name }}</a>
                                            </h5>
                                            <div class="product-price-wrap">
                                                @if($cp->type == 1)
                                                    <div class="product-price">{{ $cp->regular_price }} Tk</div>
                                                @elseif($cp->type == 2)
                                                    <div class="product-price">
                                                        {{ $cp->details->min('regular_price') }} Tk – {{ $cp->details->max('regular_price') }} Tk
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-button-wrap">
                                            <div class="product-button">
                                                <a class="button button-primary-2 button-zakaria fl-bigmug-line-search74" href="{{ route('product', $cp->slug) }}" aria-label="Product Details"></a>
                                            </div>
                                            <div class="product-button">
                                                <a class="button button-primary-2 button-zakaria fl-bigmug-line-shopping202" href="{{ route('cart') }}" aria-label="Add to Cart"></a>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        @endforeach
                    </div>
                    <a href="{{ route('category', $category->slug) }}" class="view-all-circle" aria-label="View All Products">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
</section>
