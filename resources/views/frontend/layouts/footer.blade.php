<footer class="section footer-modern ">
    <div class="footer-modern-body section-xl  bg-image-1 pb-5">
        <div class="container">
            <div class="row row-40 row-md-50 justify-content-xl-between">

                {{-- Contact Info --}}
                <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3 wow fadeInRight" data-wow-delay=".2s">
                    <h5 class="footer-modern-title">Get in touch</h5>
                    <ul class="contacts-creative">
                        <li>
                            <div class="unit unit-spacing-sm flex-column flex-md-row">
                                <div class="unit-left"><span class="icon mdi mdi-map-marker"></span></div>
                                <div class="unit-body text-black">
                                    160/485 Mokdom Ali Sarker Road, Dhour, Turag, <br> Dhaka - 1230.
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="unit unit-spacing-sm flex-column flex-md-row">
                                <div class="unit-left"><span class="icon mdi mdi-phone"></span></div>
                                <div class="unit-body"><a href="tel:+8801712969807">+8801712969807</a></div>
                            </div>
                        </li>
                        <li>
                            <div class="unit unit-spacing-sm flex-column flex-md-row">
                                <div class="unit-left"><span class="icon mdi mdi-email-outline"></span></div>
                                <div class="unit-body"><a href="mailto:{{ $settings->email }}">{{ $settings->email }}</a></div>
                            </div>
                        </li>
                    </ul>
                    <ul class="list-inline list-social-3 list-inline-sm">
                        <li><a class="icon mdi mdi-facebook icon-xxs" href="{{ $settings->facebook_url }}"></a></li>
                        <li><a class="icon mdi mdi-twitter icon-xxs" href="{{ $settings->twitter_url }}"></a></li>
                        <li><a class="icon mdi mdi-instagram icon-xxs" href="{{ $settings->instagram_url }}"></a></li>
                        <li><a class="icon mdi mdi-youtube-play icon-xxs" href="{{ $settings->youtube_url }}"></a></li>
                    </ul>
                </div>



                {{-- Extra Links --}}

                <div class="col-sm-6 col-md-5 col-lg-4 col-xl-3 wow fadeInRight" data-wow-delay=".2s">
                    <h5 class="footer-modern-title">Mr. Baker</h5>
                    <ul class="contacts-creative">
                        <li><a class="rd-megamenu-list-link" href="{{ route('outlets') }}">Our Outlets</a></li>
                        <li><a class="rd-megamenu-list-link" href="{{ route('page', ['slug' => 'refund-and-return-policy']) }}">Refund and Return Policy</a></li>
                        <li><a class="rd-megamenu-list-link" href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy Policy</a></li>
                        <li><a class="rd-megamenu-list-link" href="{{ route('page', ['slug' => 'terms-conditions']) }}">Terms & Conditions</a></li>
                    </ul>

                </div>

                {{-- Quick Links --}}
                <div class="col-sm-6 col-md-7 col-lg-5 wow fadeInRight" data-wow-delay=".1s">
                    <h5 class="footer-modern-title">Quick Links</h5>
                    <ul class="footer-modern-list footer-modern-list-2 d-sm-inline-block d-md-block">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('page', ['slug' => 'about-mr-baker']) }}">About Us</a></li>
                        <li><a href="{{ route('shop')  }}">Shop</a></li>
                        <li><a href="{{ route('contact')  }}">Contact Us</a></li>
                        <li><a href="{{ route('cart')  }}">Cart</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register')  }}">Register</a></li>
                    </ul>
                    <img class="footer-modern-logo" src="{{ asset('images/SSL-Commerz-Pay-With-logo-All-Size-01.png') }}">
                </div>

            </div>
        </div>
    </div>
    <div class="footer-modern-panel text-center" style="background-color: #2f3194;">
        <div class="container">
            <p class="rights text-white">
                <span>&copy;&nbsp;</span>
                <span class="copyright-year"></span>
                <span>.&nbsp;</span>
                <span> Mr. Baker Cake & Pastry Shop Ltd. </span>
                <span> All Rights Reserved</span><span>.&nbsp;</span>
            </p>
        </div>
    </div>
</footer>
