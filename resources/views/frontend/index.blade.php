<!DOCTYPE html>
<html class="wide wow-animation" lang="en">
  <head>
    @include('frontend.layouts.analytics-head')
    <title>@yield('seo_title', Cache::get('settings')->site_title)</title>
    @include('frontend.layouts.meta')
    @include('frontend.layouts.style')
  </head>
  <body>
  @include('frontend.layouts.analytics-body')
  <div class="preloader">
      <div class="preloader-body">
          <div class="cssload-bell">
              <div class="cssload-circle">
                  <div class="cssload-inner"></div>
              </div>
              <div class="cssload-circle">
                  <div class="cssload-inner"></div>
              </div>
              <div class="cssload-circle">
                  <div class="cssload-inner"></div>
              </div>
              <div class="cssload-circle">
                  <div class="cssload-inner"></div>
              </div>
              <div class="cssload-circle">
                  <div class="cssload-inner"></div>
              </div>
          </div>
      </div>
  </div>
    <div class="page">
      <!-- Page Header-->
      <header class="section page-header">

        <!-- RD Navbar-->
        <div class="rd-navbar-wrap" style="height: 100%;">
          <nav class="rd-navbar rd-navbar-modern" data-layout="rd-navbar-fixed" data-sm-layout="rd-navbar-fixed" data-md-layout="rd-navbar-fixed" data-md-device-layout="rd-navbar-fixed" data-lg-layout="rd-navbar-static" data-lg-device-layout="rd-navbar-fixed" data-xl-layout="rd-navbar-static" data-xl-device-layout="rd-navbar-static" data-xxl-layout="rd-navbar-static" data-xxl-device-layout="rd-navbar-static" data-lg-stick-up-offset="100px" data-xl-stick-up-offset="120px" data-xxl-stick-up-offset="140px" data-lg-stick-up="true" data-xl-stick-up="true" data-xxl-stick-up="true">
            @include('frontend.layouts.topbar')
            @include('frontend.layouts.menu')

          </nav>
        </div>
      </header>
      @yield('content')
      <!-- Page Footer-->
      @include('frontend.layouts.footer')
    </div>
    <div class="snackbars" id="form-output-global"></div>
    @include('frontend.layouts.script')
  </body>
</html>
