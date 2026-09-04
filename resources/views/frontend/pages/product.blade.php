@extends('frontend.index')
@section('seo_title', ($product->meta_title ?: $product->name) . ' | ' . ($settings->site_title ?? 'Mr. Baker'))
@section('seo_description', $product->meta_description ?: ($product->short_desc ?: strip_tags($product->description)))
@section('seo_keywords', $product->meta_keywords)
@section('seo_type', 'product')
@section('page-styles')
    @include('frontend.style.product')
@stop
@section('content')
    <!-- Single Product -->
    <hr>
    <section class="section section-sm section-first bg-default pt-5">
        <div class="container">
            <div class="row row-30 ">
                <div class="col-lg-6">
                    <div class="slick-vertical slick-product">
                        <!-- Slick Carousel -->
                        <div class="slick-slider carousel-parent" id="carousel-parent" data-items="1" data-swipe="true" data-child="#child-carousel" data-for="#child-carousel">
                            <!-- Main Product Image -->
                            <div class="item">
                                <div class="slick-product-figure">
                                    @if ($product->image && file_exists(public_path($product->image->path . '/' . $product->image->name)))
                                        <img src="{{ $product->image->optimized_url }}" width="530" height="480" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" width="530" height="480">
                                    @endif
                                </div>
                            </div>
                            <!-- Additional Product Images -->
                            @foreach($product->images as $productImage)
                                <div class="item">
                                    <div class="slick-product-figure">
                                        @if ($productImage && file_exists(public_path($productImage->path . '/' . $productImage->name)))
                                            <img src="{{ $productImage->optimized_url }}" loading="lazy" decoding="async" width="530" height="480" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" width="530" height="480">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Thumbnail Navigation -->
                        <div class="slick-slider child-carousel slick-nav-1" id="child-carousel"
                             data-arrows="true" data-items="3" data-sm-items="3" data-md-items="3"
                             data-lg-items="3" data-xl-items="3" data-xxl-items="3" data-md-vertical="true"
                             data-for="#carousel-parent">
                            <!-- Main Image Thumb -->
                            <div class="item">
                                <div class="slick-product-figure">
                                    @if ($product->image && file_exists(public_path($product->image->path . '/' . $product->image->name)))
                                        <img src="{{ $product->image->optimized_url }}" width="530" height="480" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" width="530" height="480">
                                    @endif
                                </div>
                            </div>
                            <!-- Additional Thumbnails -->
                            @foreach($product->images as $productImage)
                                <div class="item">
                                    <div class="slick-product-figure">
                                        @if ($productImage && file_exists(public_path($productImage->path . '/' . $productImage->name)))
                                            <img src="{{ $productImage->optimized_url }}" loading="lazy" decoding="async" width="530" height="480" alt="{{ $product->name }}">
                                        @else
                                            <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" width="530" height="480">
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="single-product">
                        <h3 class="text-transform-none fw-medium">{{ $product->name }}</h3>
                        <div class="group-md group-middle">
                            <div class="single-product-price">
                                @if($product->type == 1)
                                    <div class="single-product-price">{{ $product->regular_price }} Tk</div>
                                @endif
                                @if($product->type == 2)
                                    <div class="single-product-price">
                                        {{ $product->details->min('regular_price') }} Tk
                                        –
                                        {{ $product->details->max('regular_price') }} Tk
                                    </div>
                                @endif
                            </div>
                            <div class="single-product-rating">
                                <span class="icon mdi mdi-star"></span>
                                <span class="icon mdi mdi-star"></span>
                                <span class="icon mdi mdi-star"></span>
                                <span class="icon mdi mdi-star"></span>
                                <span class="icon mdi mdi-star-half"></span>
                            </div>
                        </div>
                        <ul class="list list-description mb-3">
                            <li><span>Categories:</span><span>{{ $product->category->name }}</span></li>
                            <li><span>{{ $product->short_desc }}</span><span></span></li>
                        </ul>

                        <!-- Add to Cart Form -->
                        <form method="POST" action="{{ route('cart.add') }}" id="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="slug" value="{{ $product->slug }}">
                            <input type="hidden" name="image" value="{{ $product->image->thumbnail_url ?? asset('images/products/placeholder.png') }}">

                            @if($product->type == 2 || $product->category->is_customized)
                                <div class="row">
                                    @if($product->type == 2)
                                        <div class="col-md-12 mb-3">
                                            <div class="form-wrap w-100" style="position: relative;">
                                                <div class="custom-select-mimic" style="position: relative;">
                                                    <input type="hidden" name="variant_id" id="variant_id" value="{{ old('variant_id') }}">
                                                    <button type="button" id="variant-dropdown-btn" class="form-input" style="width: 100%; text-align: left; background: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; cursor: pointer;">
                <span id="variant-selected">
                    {{ old('variant_id') ? $product->details->firstWhere('id', old('variant_id'))->size . ' - ' . ($product->details->firstWhere('id', old('variant_id'))->special_price ?? $product->details->firstWhere('id', old('variant_id'))->regular_price) . ' Tk' : 'Select Cake Size & Flavour' }}
                </span>
                                                        <span style="float: right;">&#9662;</span>
                                                    </button>
                                                    <ul id="variant-dropdown-list" style="display: none; position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 0 0 4px 4px; margin: 0; padding: 0; list-style: none;">
                                                        @foreach($product->details as $pd)
                                                            <li class="dropdown-item" data-value="{{ $pd->id }}" data-price="{{ $pd->special_price ?? $pd->regular_price }}" style="padding: 10px; cursor: pointer;">
                                                                {{ $pd->size }} - {{ $pd->special_price ?? $pd->regular_price }} Tk
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div id="variant-error" class="error-message"></div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6"></div>

                                    {{--@if($product->category->is_customized)
                                            <div class="col-md-12 mb-3">
                                                <div class="form-wrap w-100" style="position: relative;">

                                                    <div class="custom-select-mimic" style="position: relative;">
                                                        <input type="hidden" name="flavour" id="flavour" value="{{ old('flavour') }}">
                                                        <button type="button" id="flavour-dropdown-btn" class="form-input" style="width: 100%; text-align: left; background: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; cursor: pointer;">
                                                            <span id="flavour-selected">{{ old('flavour') ? old('flavour') : 'Select Flavour' }}</span>
                                                            <span style="float: right;">&#9662;</span>
                                                        </button>
                                                        <ul id="flavour-dropdown-list" style="display: none; position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 0 0 4px 4px; margin: 0; padding: 0; list-style: none;">
                                                            @foreach($flavours as $flavour)
                                                                <li class="dropdown-item" data-value="{{ $flavour->name }}" style="padding: 10px; cursor: pointer;">{{ $flavour->name }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                    <div id="flavour-error" class="error-message"></div>
                                                </div>
                                            </div>
                                    @endif--}}
                                </div>
                            @endif

                            @if($product->type == 2)
                                <div class="mb-3">
                                    <input type="text" class="form-input" name="level" id="level" placeholder="Type Cake Level (Optional)" maxlength="100">
                                    <input type="hidden" name="price" id="price-field">
                                    <div id="level-error" class="error-message"></div>
                                </div>
                            @else
                                <input type="hidden" name="price" value="{{ $product->special_price ?? $product->regular_price }}">
                            @endif

                            @if($product->category->is_customized)
                                <div class="mb-3">
                                    <textarea class="form-input" name="custom_note" id="custom_note" rows="3" placeholder="Add a note for customization (Optional)" maxlength="500"></textarea>
                                    <div id="custom-note-error" class="error-message"></div>
                                    <small class="text-muted">Maximum 500 characters</small>
                                </div>

                                <div class="text-danger" style="font-size: 14px; padding: 8px 2px;">
                                    <strong>Same-day and Custom Cake Orders Please note:</strong> Custom/Special Cakes must be ordered between 7:00 AM and 5:00 PM for same-day delivery. Orders require a minimum of 6 hours of preparation time before delivery.
                                    Need a cake in a hurry? Call us—we want to make your event even sweeter!<br>
                                    And Some customization may add extra cost, including decoration charges.<br>
                                    <strong>একই দিনে আর কাস্টম কেক অর্ডার :</strong>
                                        দয়া করে লক্ষ্য করুন: কাস্টম/স্পেশাল কেক একই দিনে ডেলিভারির জন্য সকাল ৭টা থেকে বিকাল ৫টার মধ্যে অর্ডার করতে হবে। ডেলিভারির আগে অন্তত ৬ ঘণ্টা প্রস্তুতির সময় প্রয়োজন।
                                        হঠাৎ কেক দরকার? আমাদের কল করুন – আমরা চাই আপনার অনুষ্ঠানটাকে আরও মিষ্টি করতে!<br>
                                        কিছু কাস্টমাইজেশনের জন্য বাড়তি খরচ যোগ হতে পারে, যেমন ডেকোরেশন চার্জ।
                                </div>
                            @endif

                            <hr class="hr-gray-100">
                            <div class="group-xs group-middle mb-3">
                                <div class="product-stepper">
                                    <input class="form-input" type="number" name="quantity" id="quantity" value="1" min="1" max="1000" data-zeros="true">
                                    <div id="quantity-error" class="error-message"></div>
                                </div>
                                <div>
                                    <button type="submit" class="button button-lg button-secondary button-zakaria" id="add-to-cart-btn">
                                        <span class="btn-text">Add to Cart</span>
                                        <span class="btn-loading" style="display: none;">Adding...</span>
                                    </button>
                                </div>
                            </div>

                            <!-- General error message -->
                            <div id="general-error" class="error-message" style="margin-bottom: 15px;"></div>
                        </form>
                        <hr class="hr-gray-100">
                        @php
                            $productUrl = urlencode(route('product', $product->slug));
                            $productName = urlencode($product->name);
                        @endphp
                        <div class="group-xs group-middle">
                            <span class="list-social-title">Share</span>
                            <div>
                                <ul class="list-inline list-social list-inline-sm">
                                    <li>
                                        <a class="icon mdi mdi-facebook"
                                           href="https://www.facebook.com/sharer/sharer.php?u={{ $productUrl }}"
                                           target="_blank" rel="noopener noreferrer"></a>
                                    </li>
                                    <li>
                                        <a class="icon mdi mdi-twitter"
                                           href="https://twitter.com/intent/tweet?url={{ $productUrl }}&text={{ $productName }}"
                                           target="_blank" rel="noopener noreferrer"></a>
                                    </li>
                                    <li>
                                        <a class="icon mdi mdi-whatsapp"
                                           href="https://api.whatsapp.com/send?text={{ $productName }}%20{{ $productUrl }}"
                                           target="_blank" rel="noopener noreferrer"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <section class="section section-sm section-last bg-default" style="padding-bottom: 65px;!important">
        <div class="container">
            <h4 class="fw-sbold">Products You Might Also Like</h4>
            <div class="row row-lg row-30 row-lg-50 justify-content-center">
                @foreach ($related_products as $related)
                    <div class="col-6 col-sm-6 col-md-5 col-lg-3">
                        <!-- Product -->
                        <article class="product"  style="min-height: 380px;">
                            <div class="product-body">
                                <a href="{{ route('product', $related->slug) }}">
                                    <div class="product-figure">
                                        @if ($related->image && file_exists(public_path($related->image->path . '/' . $related->image->name)))
                                            <img src="{{ file_exists(public_path($related->image->path . '/thumbs/' . $related->image->name)) ? $related->image->optimized_thumbnail_url : $related->image->optimized_url }}" loading="lazy" decoding="async" class="img-responsive" width="300" height="170" style="width: 300px; height: 170px;" alt="{{ $related->name }}">
                                        @else
                                            <img src="{{ asset('images/products/placeholder.png') }}" class="img-responsive" alt="Image"  style="width: 300px; height: 170px;" width="300" height="170">
                                        @endif
                                    </div>
                                </a>
                                <h5 class="product-title">
                                    <a href="{{ route('product', $related->slug) }}">{{ $related->name }}</a>
                                </h5>
                                <div class="product-price-wrap">
                                    @if($related->type == 1)
                                        <div class="product-price">{{ $related->regular_price }} Tk</div>
                                    @endif
                                    @if($related->type == 2)
                                        <div class="product-price">
                                            {{ $related->details->min('regular_price') }} Tk
                                            –
                                            {{ $related->details->max('regular_price') }} Tk
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if ($related->discount_price)
                                <span class="product-badge product-badge-sale">Sale</span>
                            @endif
                            <div class="product-button-wrap">
                                <div class="product-button">
                                    <a class="button button-primary-2 button-zakaria fl-bigmug-line-search74" href="{{ route('product', $related->slug) }}"></a>
                                </div>
                                @if($related->type == 1)
                                    <div class="product-button" style="margin-top: -28px;!important">
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $related->id }}">
                                            <input type="hidden" name="name" value="{{ $related->name }}">
                                            <input type="hidden" name="price" value="{{ $related->regular_price }}">
                                            <input type="hidden" name="slug" value="{{ $related->slug }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="image" value="{{ $related->image->thumbnail_url ?? asset('images/products/placeholder.png') }}">
                                            <button type="submit" class="button button-primary-2 button-zakaria fl-bigmug-line-shopping202"></button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <hr>

    <section class="section section-sm section-last bg-default">
        <div class="container">
            <h4 class="fw-sbold">Best Selling Products</h4>
            <div class="row row-lg row-30 row-lg-50 justify-content-center">
                @foreach ($best_selling as $related)
                    <div class="col-6 col-sm-6 col-md-5 col-lg-3">
                        <!-- Product -->
                        <article class="product"  style="min-height: 380px;">
                            <div class="product-body">
                                <a href="{{ route('product', $related->slug) }}">
                                    <div class="product-figure">
                                        @if ($related->image && file_exists(public_path($related->image->path . '/' . $related->image->name)))
                                            <img src="{{ file_exists(public_path($related->image->path . '/thumbs/' . $related->image->name)) ? $related->image->optimized_thumbnail_url : $related->image->optimized_url }}" loading="lazy" decoding="async" class="img-responsive" width="300" height="170" style="width: 300px; height: 170px;" alt="{{ $related->name }}">
                                        @else
                                            <img src="{{ asset('images/products/placeholder.png') }}"  class="img-responsive" alt="Image"  style="width: 300px; height: 170px;" width="300" height="170">
                                        @endif
                                    </div>
                                </a>
                                <h5 class="product-title">
                                    <a href="{{ route('product', $related->slug) }}">{{ $related->name }}</a>
                                </h5>
                                <div class="product-price-wrap">
                                    @if($related->type == 1)
                                        <div class="product-price">{{ $related->regular_price }} Tk</div>
                                    @endif
                                    @if($related->type == 2)
                                        <div class="product-price">
                                            {{ $related->details->min('regular_price') }} Tk
                                            –
                                            {{ $related->details->max('regular_price') }} Tk
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if ($related->discount_price)
                                <span class="product-badge product-badge-sale">Sale</span>
                            @endif
                            <div class="product-button-wrap">
                                <div class="product-button">
                                    <a class="button button-primary-2 button-zakaria fl-bigmug-line-search74" href="{{ route('product', $related->slug) }}"></a>
                                </div>
                                @if($related->type == 1)
                                    <div class="product-button" style="margin-top: -28px;!important">
                                        <form method="POST" action="{{ route('cart.add') }}">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $related->id }}">
                                            <input type="hidden" name="name" value="{{ $related->name }}">
                                            <input type="hidden" name="price" value="{{ $related->regular_price }}">
                                            <input type="hidden" name="slug" value="{{ $related->slug }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <input type="hidden" name="image" value="{{ $related->image->thumbnail_url ?? asset('images/products/placeholder.png') }}">
                                            <button type="submit" class="button button-primary-2 button-zakaria fl-bigmug-line-shopping202"></button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('page-scripts')

    @if($product->type == 2)
       <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('add-to-cart-form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                let valid = true;

                // Variant validation
                const variantInput = document.getElementById('variant_id');
                const variantError = document.getElementById('variant-error');
                if (variantInput && !variantInput.value) {
                    variantError.textContent = 'Please select a cake size & flavour.';
                    variantError.style.display = 'block';
                    valid = false;
                } else if (variantError) {
                    variantError.textContent = '';
                    variantError.style.display = 'none';
                }

                // Flavour validation
                const flavourInput = document.getElementById('flavour');
                const flavourError = document.getElementById('flavour-error');
                if (flavourInput && !flavourInput.value) {
                    flavourError.textContent = 'Please select a flavour.';
                    flavourError.style.display = 'block';
                    valid = false;
                } else if (flavourError) {
                    flavourError.textContent = '';
                    flavourError.style.display = 'none';
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('flavour-dropdown-btn');
            const list = document.getElementById('flavour-dropdown-list');
            const selected = document.getElementById('flavour-selected');
            const hidden = document.getElementById('flavour');
            if (btn && list && selected && hidden) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    list.style.display = list.style.display === 'block' ? 'none' : 'block';
                });
                list.querySelectorAll('.dropdown-item').forEach(function(item) {
                    item.addEventListener('click', function() {
                        selected.textContent = this.textContent;
                        hidden.value = this.getAttribute('data-value');
                        list.style.display = 'none';
                    });
                });
                document.addEventListener('click', function(e) {
                    if (!btn.contains(e.target) && !list.contains(e.target)) {
                        list.style.display = 'none';
                    }
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('variant-dropdown-btn');
            const list = document.getElementById('variant-dropdown-list');
            const selected = document.getElementById('variant-selected');
            const hidden = document.getElementById('variant_id');
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                list.style.display = list.style.display === 'block' ? 'none' : 'block';
            });
            list.querySelectorAll('.dropdown-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    selected.textContent = this.textContent;
                    hidden.value = this.getAttribute('data-value');
                    document.getElementById('price-field').value = this.getAttribute('data-price');
                    list.style.display = 'none';
                    clearError('variant');
                });
            });
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !list.contains(e.target)) {
                    list.style.display = 'none';
                }
            });
        });
        // Configuration object to store product details
        const productConfig = {
            type: {{ $product->type ?? 1 }},
            isCustomized: {{ $product->category->is_customized ? 'true' : 'false' }},
            hasVariants: {{ $product->type == 2 ? 'true' : 'false' }}
        };

        // Update price when variant is selected
        function updatePrice() {
            const select = document.getElementById('variant_id');
            const priceField = document.getElementById('price-field');

            if (select && priceField) {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption && selectedOption.dataset.price) {
                    priceField.value = selectedOption.dataset.price;
                } else {
                    priceField.value = '';
                }
            }

            // Clear variant error when selection is made
            if (select && select.value) {
                clearError('variant');
            }
        }

        // Clear individual error
        function clearError(fieldName) {
            const errorElement = document.getElementById(fieldName + '-error');
            const inputElement = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);

            if (errorElement) {
                errorElement.textContent = '';
                errorElement.style.display = 'none';
            }

            if (inputElement) {
                inputElement.classList.remove('error');
            }
        }

        // Clear all errors
        function clearAllErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            const inputElements = document.querySelectorAll('.form-input, .form-select');

            errorElements.forEach(element => {
                element.textContent = '';
                element.style.display = 'none';
            });

            inputElements.forEach(element => {
                element.classList.remove('error');
            });
        }

        // Display error
        function showError(fieldName, message) {
            const errorElement = document.getElementById(fieldName + '-error');
            const inputElement = document.getElementById(fieldName) || document.querySelector(`[name="${fieldName}"]`);

            if (errorElement) {
                errorElement.textContent = message;
                errorElement.style.display = 'block';
            }

            if (inputElement) {
                inputElement.classList.add('error');
                inputElement.focus();
            }
        }

        // Add loading state to button
        function setButtonLoading(loading) {
            const button = document.getElementById('add-to-cart-btn');
            const btnText = button.querySelector('.btn-text');
            const btnLoading = button.querySelector('.btn-loading');

            if (loading) {
                button.disabled = true;
                btnText.style.display = 'none';
                btnLoading.style.display = 'inline';
            } else {
                button.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            }
        }

        // Real-time validation for inputs
        function setupRealTimeValidation() {
            // Quantity input
            const quantityInput = document.getElementById('quantity');
            if (quantityInput) {
                quantityInput.addEventListener('input', function() {
                    const quantity = parseInt(this.value);
                    if (quantity >= 1 && quantity <= 1000) {
                        clearError('quantity');
                    }
                });
            }

            // Level input
            const levelInput = document.getElementById('level');
            if (levelInput) {
                levelInput.addEventListener('input', function() {
                    if (this.value.trim().length <= 100) {
                        clearError('level');
                    }
                });
            }

            // Custom note textarea
            const customNoteTextarea = document.getElementById('custom_note');
            if (customNoteTextarea) {
                customNoteTextarea.addEventListener('input', function() {
                    if (this.value.trim().length <= 500) {
                        clearError('custom-note');
                    }
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize price if variant exists
            updatePrice();

            // Setup real-time validation
            setupRealTimeValidation();

            // Handle form submission
            const form = document.getElementById('add-to-cart-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    if (validateForm()) {
                        setButtonLoading(true);

                        // Submit the form
                        const formData = new FormData(form);

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                setButtonLoading(false);

                                if (data.success) {
                                    // Show success message or redirect
                                    const generalError = document.getElementById('general-error');
                                    if (generalError) {
                                        generalError.className = 'success-message';
                                        generalError.textContent = 'Product added to cart successfully!';
                                        generalError.style.display = 'block';
                                    }

                                    // Optional: Reset form or redirect
                                    setTimeout(() => {
                                        if (generalError) {
                                            generalError.style.display = 'none';
                                        }
                                    }, 3000);

                                } else {
                                    // Handle server-side errors
                                    const generalError = document.getElementById('general-error');
                                    if (generalError) {
                                        generalError.className = 'error-message';
                                        generalError.textContent = data.message || 'An error occurred. Please try again.';
                                        generalError.style.display = 'block';
                                    }
                                }
                            })
                            .catch(error => {
                                setButtonLoading(false);
                                console.error('Error:', error);

                                // For non-AJAX fallback, submit the form normally
                                form.submit();
                            });
                    }
                });
            }
        });
    </script>
    @endif
    @php
        $productPrice = $product->type == 1 ? $product->regular_price : $product->details->min('regular_price');
        $productSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => strip_tags($product->meta_description ?: ($product->short_desc ?: $product->description)),
            'url' => route('product', $product->slug),
            'image' => $product->image?->optimized_url,
            'brand' => ['@type' => 'Brand', 'name' => $settings->site_title ?? 'Mr. Baker'],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product', $product->slug),
                'priceCurrency' => 'BDT',
                'price' => $productPrice,
                'availability' => $product->availability ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            ],
        ];
    @endphp
    <script type="application/ld+json">@json($productSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endsection
