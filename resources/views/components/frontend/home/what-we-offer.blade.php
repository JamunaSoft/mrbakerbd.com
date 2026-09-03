<section class="section section-sm section-last  pt-2" style="margin-bottom: -25px; background-color: #bed3fe;">
    <div class="container">
        <h2 class="wow fadeScale mb-3 mt-5" style="font-size: 40px; font-weight: 500; ">Customers' Choice</h2>
        <div class="row">
            <!-- Product 1 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <article class="product" style="max-height: 300px;">
                    <div class="product-body">
                        <div class="product-figure">
                            <a href="{{ route('category', ['slug' => 'display-regular-cake']) }}">
                                <img src="{{ asset('images/home-3.jpg') }}" style="width: 300px; height: 170px;" alt="Image" width="300" height="200">
                            </a>
                        </div>
                        <h5 class="product-title">
                            <a href="{{ route('category', ['slug' => 'display-regular-cake']) }}">Display Cake</a>
                        </h5>
                    </div>
                    <div class="product-button-wrap" style="max-height: 300px;">
                        <div class="product-button"></div>
                    </div>
                </article>
            </div>

            <!-- Product 2 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <article class="product" style="max-height: 300px;">
                    <div class="product-body">
                        <div class="product-figure">
                            <a href="{{ route('category', ['slug' => 'pastry-chocolate']) }}">
                                <img src="{{ asset('images/pestry.jpg') }}" style="width: 300px; height: 170px;" alt="Image" width="300" height="200">
                            </a>
                        </div>
                        <h5 class="product-title">
                            <a href="{{ route('category', ['slug' => 'pastry-chocolate']) }}">Pastry & Chocolate</a>
                        </h5>
                    </div>
                    <div class="product-button-wrap" style="max-height: 300px;">
                        <div class="product-button"></div>
                    </div>
                </article>
            </div>

            <!-- Product 3 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <article class="product" style="max-height: 300px;">
                    <div class="product-body">
                        <div class="product-figure">
                            <a href="{{ route('category', ['slug' => 'biscuits-toast']) }}">
                                <img src="{{ asset('images/home-4.jpg') }}" style="width: 300px; height: 170px;" alt="Image" width="300" height="200">
                            </a>
                        </div>
                        <h5 class="product-title">
                            <a href="{{ route('category', ['slug' => 'biscuits-toast']) }}">Biscuits & Toast</a>
                        </h5>
                    </div>
                    <div class="product-button-wrap" style="max-height: 300px;">
                        <div class="product-button"></div>
                    </div>
                </article>
            </div>

            <!-- Product 4 -->
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <article class="product" style="max-height: 300px;">
                    <div class="product-body">
                        <div class="product-figure">
                            <a href="{{ route('category', ['slug' => 'special-customize-cake']) }}">
                                <img src="{{ asset('images/home-2.jpg') }}" style="width: 300px; height: 170px;" alt="Image" width="300" height="200">
                            </a>
                        </div>
                        <h5 class="product-title">
                            <a href="{{ route('category', ['slug' => 'special-customize-cake']) }}">Customized Cake</a>
                        </h5>
                    </div>
                    <div class="product-button-wrap" style="max-height: 300px;">
                        <div class="product-button"></div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
