@extends('index')
@section('content')
      <section class="breadcrumbs-custom">
        <div class="parallax-container" data-parallax-img="images/breadcrumbs-bg.jpg">
          <div class="breadcrumbs-custom-body parallax-content context-dark">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">Forms</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="index.html">Home</a></li>
              <li><a href="#">Elements</a></li>
              <li class="active">Forms</li>
            </ul>
          </div>
        </div>
      </section>
      <!-- Forms-->
      <section class="section section-xxl bg-default text-md-start">
        <div class="container">
          <div class="row row-40 justify-content-center justify-content-md-between">
            <div class="col-md-3"></div>
            <div class="col-sm-10 col-md-6">
              <div class="inset-xl-right-50">
                <h3>Login form</h3>
                <div class="group group-middle group-button-1"><a class="button button-google button-icon button-icon-left button-round" href="#"><span class="icon fa fa-google-plus"></span>Google+</a><a class="button button-twitter button-icon button-icon-left button-round" href="#"><span class="icon fa fa-twitter"></span>Twitter</a><a class="button button-facebook button-icon button-icon-left button-round" href="#"><span class="icon fa fa-facebook"></span>Facebook</a></div>
                <!-- RD Mailform-->
                <form class="rd-form rd-mailform">
                  <div class="form-wrap">
                    <input class="form-input" id="login-name" type="text" name="name">
                    <label class="form-label" for="login-name">Username</label>
                  </div>
                  <div class="form-wrap">
                    <input class="form-input" id="login-password" type="password" name="password">
                    <label class="form-label" for="login-password">Password</label>
                  </div>
                  <label class="checkbox-inline">
                    <input name="input-checkbox-1" value="checkbox-1" type="checkbox">Remember Me
                  </label>
                  <div class="group-sm group-middle group-button-1">
                    <button class="button button-lg button-primary button-zakaria" type="submit">Sign In</button><a class="button button-lg button-secondary button-zakaria" href="#">Create an account</a>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-md-3"></div>
          </div>
        </div>
      </section>
      <!-- Subscribe to Our Newsletter-->
      <section class="section parallax-container" data-parallax-img="images/parallax-1.jpg">
        <div class="parallax-content section-xxl context-light text-md-start">
          <div class="container">
            <div class="row row-30 justify-content-center align-items-center align-items-md-end">
              <div class="col-lg-3">
                <h3 class="text-spacing-100 wow fadeInLeft">Stay <span class="fw-light">connected</span>
                </h3>
                <p class="wow fadeInLeft" data-wow-delay=".1s">Subscribe to our newsletter</p>
              </div>
              <div class="col-lg-8 col-xl-9 inset-lg-bottom-10">
                <!-- RD Mailform-->
                <form class="rd-form rd-mailform rd-form-inline form-lg rd-form-text-center" data-form-output="form-output-global" data-form-type="subscribe" method="post">
                  <div class="form-wrap wow fadeInUp">
                    <input class="form-input" id="subscribe-form-0-email" type="email" name="email"/>
                    <label class="form-label" for="subscribe-form-0-email">Enter your e-mail address</label>
                  </div>
                  <div class="form-button wow fadeInRight">
                    <button class="button button-shadow-2 button-zakaria button-lg button-primary" type="submit">Subscribe</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
@endsection
