@extends('frontend.index')
@section('seo_title', 'Mr. Baker Outlets | Find a Bakery Near You')
@section('seo_description', 'Find your nearest Mr. Baker outlet in Bangladesh. Search by area or branch name and view bakery addresses, contact numbers and directions.')
@section('seo_canonical', route('outlets'))
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
              <h1 class="breadcrumbs-custom-title">Our Outlets</h1>
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
                <div class="mb-4" id="outlet-search-controls" hidden>
                    <label class="d-block mb-2" for="outlet-search">Find your nearest outlet</label>
                    <input class="form-control" id="outlet-search" type="search" placeholder="Search by outlet name, area or address" autocomplete="off" aria-controls="faqAccordion">
                    <p class="mt-2" id="outlet-result-count" role="status" aria-live="polite"></p>
                </div>
                <p class="mb-4">Explore our bakery outlets. Select an outlet for its address and contact number, or open its details for directions.</p>
                <p id="outlet-no-results" class="text-center" hidden>No matching outlets found. Try another area or outlet name.</p>
                <div id="faqAccordion">
                    <div class="row g-4">
                        @forelse($outlets as $outlet)
                        <div class="col-md-4 outlet-card" data-outlet-search="{{ $outlet->name }} {{ $outlet->address }} {{ $outlet->phone }}">
                            <button class="btn modern-btn w-100" type="button" data-bs-toggle="collapse" data-bs-target="#outlet-{{ $outlet->id }}" aria-expanded="false" aria-controls="outlet-{{ $outlet->id }}">
                                {{ $outlet->name }}
                            </button>
                            <div class="collapse mt-2" id="outlet-{{ $outlet->id }}" data-bs-parent="#faqAccordion">
                                <div class="card card-body">
                                    {{ $outlet->address }}
                                    @if($outlet->phone)
                                        <div>Contact: <a href="tel:{{ $outlet->phone }}">{{ $outlet->phone }}</a></div>
                                    @endif
                                    <a class="mt-2" href="{{ route('outlets.show', $outlet) }}">View outlet details &amp; directions</a>
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

@section('page-scripts')
    @php
        $outletListSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'ItemList',
            'name' => 'Mr. Baker Outlets',
            'itemListElement' => $outlets->values()->map(fn ($outlet, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => 'Mr. Baker - ' . $outlet->name,
                'url' => route('outlets.show', $outlet),
            ])->all(),
        ];
    @endphp
    <script type="application/ld+json">@json($outletListSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)</script>
    <script src="{{ asset('frontend/js/outlet-search.js') }}" defer></script>
@endsection
