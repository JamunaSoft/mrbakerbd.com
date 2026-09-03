@extends('frontend.index')
@section('page-styles')
    @include('frontend.style.feedback')

@stop
@section('content')

    <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="{{asset('images/feedback.jpg')}}">
            <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
                <div class="container">
                    <h2 class="breadcrumbs-custom-title">Customer Feedback</h2>
                </div>
            </div>
        </div>
        <div class="breadcrumbs-custom-footer">
            <div class="container">
                <ul class="breadcrumbs-custom-path">
                    <li><a href="{{route('home')}}">Home</a></li>
                    <li class="active">Feedback</li>
                </ul>
            </div>
        </div>
    </section>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Customer Feedback Section -->
    <section class="section section-xl bg-default text-md-start pt-5">
        <div class="container">
            <div class="mt-5">
                <div class="title-classic">
                    <h3 class="title-classic-title">Share Your Experience</h3>
                    <p class="title-classic-subtitle">We value your feedback! Please share your experience with Mr Baker to help us improve our products and services.</p>
                </div>

                <form action="{{ route('feedback.submit') }}" method="post">
                    @csrf
                    <div class="row row-20 row-md-30">
                        <!-- Customer Information -->
                        <div class="col-lg-12">
                            <div class="row row-20 row-md-30">
                                <div class="col-sm-6">
                                    <div class="form-wrap">
                                        <input class="form-input" id="customer-name" type="text" name="customer_name" placeholder="Full Name" required/>
                                    </div>
                                    <div id="name-error" class="text-danger mt-1" style="display:none;">Please enter your name.</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-wrap">
                                        <input class="form-input" id="customer-email" type="email" name="email" placeholder="Email" required/>
                                    </div>
                                    <div id="name-error" class="text-danger mt-1" style="display:none;">Please enter your name.</div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-wrap">
                                        <input class="form-input" id="customer-phone" type="text" name="phone" placeholder="Phone"/>
                                    </div>
                                </div>
                                <div class="col-sm-6 d-flex align-items-end">
                                    <div class="form-wrap w-100" style="position: relative;">
                                        <div class="custom-select-mimic" style="position: relative;">
                                            <input type="hidden" name="reason" id="reason-hidden" value="{{ old('reason') }}">
                                            <button type="button" id="reason-dropdown-btn" class="form-input" style="width: 100%; text-align: left; background: #fff; border: 1px solid #ced4da; border-radius: 4px; padding: 10px; cursor: pointer;">
                                                <span id="reason-selected">{{ old('reason') ? old('reason') : 'Select Reason' }}</span>
                                                <span style="float: right;">&#9662;</span>
                                            </button>
                                            <ul id="reason-dropdown-list" style="display: none; position: absolute; z-index: 10; width: 100%; background: #fff; border: 1px solid #ced4da; border-radius: 0 0 4px 4px; margin: 0; padding: 0; list-style: none;">
                                                <li class="dropdown-item" data-value="Product Taste/Quality" style="padding: 10px; cursor: pointer;">Product Taste/Quality</li>
                                                <li class="dropdown-item" data-value="Outlet service" style="padding: 10px; cursor: pointer;">Outlet Service</li>
                                                <li class="dropdown-item" data-value="Delivery" style="padding: 10px; cursor: pointer;">Delivery</li>
                                                <li class="dropdown-item" data-value="Website Order" style="padding: 10px; cursor: pointer;">Website Order</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div id="reason-error" class="text-danger mt-1" style="display:none;">Please select a reason.</div>
                                </div>
                        </div>

                        <!-- Feedback Message -->
                        <div class="col-lg-12">
                            <div class="form-wrap">
                                <textarea class="form-input textarea-lg" id="feedback-message" name="feedback_message" rows="6" placeholder="Write your feedback" required></textarea>
                            </div>
                        </div>
                        <div id="feedback-error" class="text-danger mt-1" style="display:none;">Please enter your feedback.</div>
                    </div>
                    </div>
                    <!-- Rating Section -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="rating-section">
                                <div class="star-rating" data-rating="overall_rating">
                                    <input type="radio" name="overall_rating" value="5" id="rating-5" required>
                                    <label for="rating-5">★</label>
                                    <input type="radio" name="overall_rating" value="4" id="rating-4">
                                    <label for="rating-4">★</label>
                                    <input type="radio" name="overall_rating" value="3" id="rating-3">
                                    <label for="rating-3">★</label>
                                    <input type="radio" name="overall_rating" value="2" id="rating-2">
                                    <label for="rating-2">★</label>
                                    <input type="radio" name="overall_rating" value="1" id="rating-1">
                                    <label for="rating-1">★</label>
                                </div>
                                <div class="rating-text mt-2">
                                    <small class="text-muted">Click on the stars to rate your experience</small>
                                </div>
                            </div>
                            <div id="rating-error" class="text-danger mt-1" style="display:none;">Please select a rating.</div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <button class="button button-lg button-secondary button-zakaria" type="submit">Submit Feedback</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
@section('page-scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('reason-dropdown-btn');
            const list = document.getElementById('reason-dropdown-list');
            const selected = document.getElementById('reason-selected');
            const hidden = document.getElementById('reason-hidden');
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
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const reason = document.getElementById('reason-hidden').value;
                const error = document.getElementById('reason-error');
                if (!reason) {
                    e.preventDefault();
                    error.style.display = 'block';
                } else {
                    error.style.display = 'none';
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                let valid = true;

                // Name
                const name = document.getElementById('customer-name').value.trim();
                const nameError = document.getElementById('name-error');
                if (!name) {
                    nameError.style.display = 'block';
                    valid = false;
                } else {
                    nameError.style.display = 'none';
                }

                // Email
                const email = document.getElementById('customer-email').value.trim();
                const emailError = document.getElementById('email-error');
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailPattern.test(email)) {
                    emailError.style.display = 'block';
                    valid = false;
                } else {
                    emailError.style.display = 'none';
                }

                // Reason
                const reason = document.getElementById('reason-hidden').value;
                const reasonError = document.getElementById('reason-error');
                if (!reason) {
                    reasonError.style.display = 'block';
                    valid = false;
                } else {
                    reasonError.style.display = 'none';
                }

                // Feedback
                const feedback = document.getElementById('feedback-message').value.trim();
                const feedbackError = document.getElementById('feedback-error');
                if (!feedback) {
                    feedbackError.style.display = 'block';
                    valid = false;
                } else {
                    feedbackError.style.display = 'none';
                }

                // Rating
                const ratingInputs = document.querySelectorAll('input[name="overall_rating"]');
                let ratingChecked = false;
                ratingInputs.forEach(input => { if (input.checked) ratingChecked = true; });
                const ratingError = document.getElementById('rating-error');
                if (!ratingChecked) {
                    ratingError.style.display = 'block';
                    valid = false;
                } else {
                    ratingError.style.display = 'none';
                }

                if (!valid) e.preventDefault();
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const starRating = document.querySelector('.star-rating');
            const stars = starRating.querySelectorAll('label');
            const inputs = starRating.querySelectorAll('input');

            // Highlight stars on hover
            stars.forEach((star, index) => {
                star.addEventListener('mouseenter', function() {
                    highlightStars(stars, index);
                });
            });

            // Clear stars on mouse leave
            starRating.addEventListener('mouseleave', function() {
                const checkedInput = starRating.querySelector('input:checked');
                if (checkedInput) {
                    const checkedIndex = Array.from(inputs).indexOf(checkedInput);
                    highlightStars(stars, checkedIndex);
                } else {
                    clearStars(stars);
                }
            });

            // Keep stars highlighted on selection
            inputs.forEach((input, index) => {
                input.addEventListener('change', function() {
                    highlightStars(stars, index);
                });
            });

            function highlightStars(stars, index) {
                stars.forEach((star, i) => {
                    star.style.color = i <= index ? '#ffc107' : '#ddd';
                });
            }

            function clearStars(stars) {
                stars.forEach(star => {
                    star.style.color = '#ddd';
                });
            }
        });
    </script>
@stop
