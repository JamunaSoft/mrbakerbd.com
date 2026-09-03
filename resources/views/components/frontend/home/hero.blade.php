<style>

    .responsive-banner {
        background-size: cover;
        background-position: center;
        position: relative;
        background-image: url('images/slides/banner-with-texture2.jpg'); /* Default for large screens */
    }

    /* Laptop (between 992px and 1199px) */
    @media (max-width: 1199.98px) {
        .responsive-banner {
            background-image: url('images/slides/banner-laptop.jpg');
        }
    }

    /* Mobile (less than 768px) */
    @media (max-width: 767.98px) {
        .responsive-banner {
            background-image: url('images/slides/banner-mobile2.jpg');
        }
    }
    </style>

<div>
    @foreach($slides as $slide)
        <section class="section section-custom-1 section-intro responsive-banner">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-xl-5 text-start position-relative">
                        <div class="text-big text-decorative text-primary wow fadeInLeft">Delicious</div>
                        <h3 class="wow fadeInLeft fw-bold text-decor-line text-spacing-200" data-wow-delay=".2s">cakes for you</h3>
                        <h4 class="wow fadeInLeft text-spacing-100 text-transform-none text-gray-600 fw-normal" data-wow-delay=".3s">
                            <span class="d-xl-block">Mr Baker offers the best</span>
                            <span class="d-xl-block">cakes and fast delivery for you.</span>
                        </h4>
                    </div>
                </div>
            </div>
        </section>
{{--        <section class="section section-sm bg-default pb-0 pt-xl-0">
            <div class="container">
                <article class="promo-classic offset-negative-1" style="background-image: url({{ asset('images/banner-6.jpg') }});">
                    <p class="big promo-classic-text"></p>
                    <p class="big promo-classic-text" style="height: 50px;"></p>
                    <h3 class="fw-normal text-transform-none wow fadeInDown text-decorative text-primary text-spacing-75" style="visibility: visible; animation-name: fadeInDown;">Unique Flavors and Fresh Ingredients</h3>
                    <h3 class="text-spacing-75 text-black-400 fw-bol g wow fadeScale" style="visibility: visible; animation-name: fadeScale;">Special Customized Cake</h3>
                    <p>
                        <span class="d-xl-block">Experience the magic of our custom cakes, where every slice tells a unique story. </span>
                    </p>
                </article>
            </div>
        </section>--}}
    @endforeach
</div>
