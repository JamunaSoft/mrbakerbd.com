@extends('frontend.index')
@section('page-styles')
    <style>
        .modern-btn {
            background: #868e96;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
        }
        .modern-btn:hover {
            background: linear-gradient(90deg, #1e2066, #4a4cb3);
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(47, 49, 148, 0.4);
            color: white;
        }
        .modern-btn:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(47, 49, 148, 0.5);
        }
        .col-md-4 {
            margin-bottom: 1rem;
        }
    </style>
@stop
@section('content')
      <section class="breadcrumbs-custom">
          <div class="parallax-container" data-parallax-img="{{asset('images/outlets.jpg')}}">
          <div class="breadcrumbs-custom-body parallax-content context-dark" style="min-height: 20px">
            <div class="container">
              <h2 class="breadcrumbs-custom-title">Our Outlets</h2>
            </div>
          </div>
        </div>
        <div class="breadcrumbs-custom-footer">
          <div class="container">
            <ul class="breadcrumbs-custom-path">
              <li><a href="{{route('home')}}">Home</a></li>
                <li><a href="{{route('outlets')}}">Outlets</a></li>
              <li class="active"> </li>
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
            <div class="mb-5">
                {{--<h3 class="wow fadeScale text-center">Our Outlets</h3>
                <hr class="mt-5 mb-5" style="border-top: 2px solid #333; opacity: 1; width: 100%;">--}}
                <div id="faqAccordion">
                    <div class="row g-4">
                        @forelse($outlets as $outlet)
                        <div class="col-md-4">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#outlet-{{ $outlet->id }}" aria-expanded="false" aria-controls="outlet-{{ $outlet->id }}">
                                {{ $outlet->name }}
                            </button>
                            <div class="collapse mt-2" id="outlet-{{ $outlet->id }}" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    {{ $outlet->address }}
                                    @if($outlet->phone)
                                        <div>Contact: <a href="tel:{{ $outlet->phone }}">{{ $outlet->phone }}</a></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                            <div class="col-12 text-center">No outlets are available at the moment.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
      </section>
@endsection
