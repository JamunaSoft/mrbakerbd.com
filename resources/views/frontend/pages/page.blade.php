@extends('frontend.index')
@section('content')
      <section class="breadcrumbs-custom">
          <div class="parallax-container" data-parallax-img="{{asset('frontend/images/breadcrumbs-bg.jpg')}}">
          <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">{{ $page->title }}</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{ route('home') }}">Home</a></li>
              <li><a href="#">Pages</a></li>
              <li class="active">{{ $page->title }}</li>
            </ul>
          </div>
        </div>
      </section>
<section class="section section-xl bg-default text-md-start">
    <div class="container">
        <div class="row row-50">
            <div class="col-md-12">
                <h3>{{ $page->title }}</h3>
                <p>{!! $page->content !!}</p>
            </div>
        </div>
    </div>
</section>
@endsection
