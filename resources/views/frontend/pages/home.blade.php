@extends('frontend.index')
@section('seo_title', ($settings->site_title ?? 'Mr. Baker') . ' | Fresh Cakes, Pastries and Bakery Products in Bangladesh')
@section('seo_description', 'Order fresh cakes, pastries, biscuits, sweets and bakery products from Mr. Baker with convenient delivery in Bangladesh.')
@section('page-styles')
    @include('frontend.style.home')
@stop
@section('content')


    <div id="cakeCarousel" class="carousel slide responsive-banner" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($slides as $index => $slide)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <section class="carousel-slide-section"
                             style="background-image: url('{{ optimized_asset('images/slides/' . $slide->image) }}');">
                        <div class="container h-100 d-flex align-items-center">
                            <div class="row w-100">
                                <div class="col-12 col-md-8 col-lg-6 text-start">
                                    {{-- Uncomment and customize your content below --}}
                                    {{--
                                    <div class="carousel-caption-box text-white">
                                        <div class="fs-4 fw-bold">Delicious</div>
                                        <h3 class="fs-2 fw-bold">Cakes for you</h3>
                                        <p class="fs-6">Mr Baker offers the best cakes and fast delivery.</p>
                                    </div>
                                    --}}
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            @endforeach
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#cakeCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#cakeCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>

        <!-- Indicators -->
        <div class="carousel-indicators">
            @foreach($slides as $index => $slide)
                <button type="button"
                        data-bs-target="#cakeCarousel"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index == 0 ? 'active' : '' }}"
                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                        aria-label="Slide {{ $index + 1 }}">
                </button>
            @endforeach
        </div>
    </div>

    <x-frontend.home.feature-products />

    <x-frontend.home.what-we-offer />

    {{--<x-frontend.home.products />--}}

    <x-frontend.home.why-choose-us />

   {{-- <x-frontend.home.chairman-message />--}}
@endsection
