@extends('frontend.index')
@section('content')

      <section class="breadcrumbs-custom">
          <div class="parallax-container context-dark" data-parallax-img="{{asset('images/contact-us2.jpg')}}">
          <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">Contact Us</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{route('home')}}">Home</a></li>
              <li class="active">Contact Us</li>
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
      <!-- Get in touch-->
      <section class="section section-xl bg-default text-md-start pt-5">
        <div class="container">

            <div class="container mt-4">
                <div class="row g-4">
                    <!-- Address Card -->
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-geo-alt-fill text-primary fs-4 me-2"></i>
                                    <h5 class="mb-0 text-primary">Address:</h5>
                                </div>
                                <p class="text-muted  ps-2">Mr. Baker Head Office & Factory: 160/485 Mokdom Ali Sarker Road,<br> Dhour, Turag, Dhaka.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-telephone-fill text-primary fs-4 me-2"></i>
                                    <h5 class="mb-0 text-primary">Phone:</h5>
                                </div>
                                <p class="text-muted ps-2"><a href="callto:01712-969807">01712-969807</a></p>
                            </div>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-telephone-fill text-primary fs-4 me-2"></i>
                                    <h5 class="mb-0 text-primary">Facebook:</h5>
                                </div>
                                <p class="text-muted  ps-2"><a href="https://www.facebook.com/mrbakerbangladesh" target="_blank">www.fb.com/mrbakerbangladesh</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0 rounded-4">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="bi bi-envelope-fill text-primary fs-4 me-2"></i>
                                    <h5 class="mb-0 text-primary">Email:</h5>
                                </div>
                                <p class="text-muted  ps-2">mrbakerbd6@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="mt-5">

          <div class="title-classic">
            <h3 class="title-classic-title">Get in touch</h3>
            <p class="title-classic-subtitle">Fill out the contact form to ask a question about our products. We will get back to you shortly.</p>
          </div>
          <form action="{{ route('contact.submit') }}" method="post">
              @csrf
            <div class="row row-20 row-md-30">
              <div class="col-lg-8">
                <div class="row row-20 row-md-30">
                  <div class="col-sm-6">
                    <div class="form-wrap">
                      <input class="form-input" id="contact-first-name-2" type="text" name="first_name" placeholder="Fiest Name"/>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-wrap">
                      <input class="form-input" id="contact-last-name-2" type="text" name="last_name" placeholder="Last Name"/>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-wrap">
                      <input class="form-input" id="contact-email-2" type="email" name="email" placeholder="Email"/>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-wrap">
                      <input class="form-input" id="contact-phone-2" type="text" name="phone" placeholder="Phone"/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-wrap">
                  <textarea class="form-input textarea-lg" id="contact-message-2" name="message" placeholder="Write your message"></textarea>
                </div>
              </div>
            </div>
            <button class="button button-lg button-secondary button-zakaria" type="submit">Send Message</button>
          </form>
        </div>
        </div>
      </section>
@endsection
