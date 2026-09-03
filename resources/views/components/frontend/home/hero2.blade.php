<style>
    .carousel-slide-section {
        background-size: cover;
        background-position: center;
        position: relative;
        min-height: 130px; /* Mobile default */
    }

    @media (min-width: 768px) {
        .carousel-slide-section {
            min-height: 300px;
        }
    }

    @media (min-width: 992px) {
        .carousel-slide-section {
            min-height: 500px;
        }
    }

    .carousel-caption-box {
        padding: 1rem;
        background-color: rgba(0, 0, 0, 0.4); /* Optional dark overlay */
        border-radius: 0.5rem;
    }
</style>

<div id="cakeCarousel" class="carousel slide responsive-banner" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach($slides as $index => $slide)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <section class="carousel-slide-section"
                         style="background-image: url('{{ asset('images/slides/' . $slide->image) }}');">
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
