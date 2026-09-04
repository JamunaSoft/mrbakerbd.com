@extends('backend.layouts.master')

@section('page-style')
<link rel="stylesheet" id="main-stylesheet" data-version="1.3.1" href="{{ asset('backend/styles/shards-dashboards.1.3.1.min.css') }}">
@stop

@section('content')

        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar-->

            @include('backend.partials.navbar')

            <!-- End Main Navbar -->
          </div>
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Overview</span>
                <h3 class="page-title">Dashboard</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Small Stats Blocks -->
            <div class="row">
              <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="stats-small card card-small">
                  <div class="card-body px-0 pb-0">
                    <div class="d-flex px-3">
                      <div class="stats-small__data">
                        <span class="stats-small__label mb-1 text-uppercase">Customers</span>
                        <h6 class="stats-small__value count m-0">{{ $c_count }}</h6>
                      </div>
                    </div>
                    <canvas height="60" class="analytics-overview-stats-small-1"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="stats-small card card-small">
                  <div class="card-body px-0 pb-0">
                    <div class="d-flex px-3">
                      <div class="stats-small__data">
                        <span class="stats-small__label mb-1 text-uppercase">Orders</span>
                        <h6 class="stats-small__value count m-0">{{ $o_count }}</h6>
                      </div>
                    </div>
                    <canvas height="60" class="analytics-overview-stats-small-3"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="stats-small card card-small">
                  <div class="card-body px-0 pb-0">
                    <div class="d-flex px-3">
                      <div class="stats-small__data">
                        <span class="stats-small__label mb-1 text-uppercase">Pending</span>
                        <h6 class="stats-small__value count m-0">{{ $po_count }}</h6>
                      </div>
                    </div>
                    <canvas height="60" class="analytics-overview-stats-small-4"></canvas>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-3 mb-4">
                <div class="stats-small card card-small">
                  <div class="card-body px-0 pb-0">
                    <div class="d-flex px-3">
                      <div class="stats-small__data">
                        <span class="stats-small__label mb-1 text-uppercase">Completed</span>
                        <h6 class="stats-small__value count m-0">{{ $co_count }}</h6>
                      </div>
                    </div>
                    <canvas height="60" class="analytics-overview-stats-small-2"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Small Stats Blocks -->
            <div class="row">
              <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-small">
                  <div class="card-header border-bottom">
                    <h5 class="m-0">Top Selling</h5>
                    <div class="block-handle"></div>
                  </div>
                  <div class="card-body p-0" style="width: 100%; min-height: 450px;">
                    <ul class="list-group list-group-small list-group-flush">
					           @foreach($top_selling_pro as $tsp)
                      <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">{{ $tsp->name }}</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">{{ $tsp->sales }}</span>
                      </li>
					           @endforeach
                    </ul>
                  </div>
                  <div class="card-footer border-top">
                    <div class="row">
                      <div class="col">
                        <select class="custom-select custom-select-sm">
                          <option selected>This Week</option>
                          <option>This Month</option>
                          <option>This Year</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-sm-12 mb-4">
                <div class="card card-small go-stats">
                  <div class="card-header border-bottom">
                    <h5 class="m-0">Processing Orders</h5>
                    <div class="block-handle"></div>
                  </div>
                  <div class="card-body py-0" style="width: 100%; min-height: 450px;">
                    <ul class="list-group list-group-small list-group-flush">
					           @foreach($processing_orders as $po)
					           @foreach($po->details as $pod)
                      <li class="list-group-item d-flex row px-0">
                        <div class="col-lg-6 col-md-8 col-sm-8 col-6">
                        <h6 class="go-stats__label mb-1">


			              </h6>
                          <div class="go-stats__meta">
                            <span class="mr-2">
								<strong>{{ $po->name }}</strong> - <strong class="text-success">{{ $po->phone }}</strong>
							</span>
							<br>
                            <span class="mr-2">
								<strong>Placed On: </strong>
								<strong class="text-danger">{{ Carbon\Carbon::parse($po->created_at)->diffForhumans() }}</strong>
							</span>
							<br>
                            <span class="mr-2">
								<strong class="text-info">@if(!is_null($po->delv_dt)){{ DateTime::createFromFormat('Y-m-d', explode(',', $po->delv_dt)[0])->format(Cache::get('settings')->date_format) }}, {{ explode(',', $po->delv_dt)[1] }}@endif</strong>
							</span>
                          </div>
                        </div>
                        <div class="col-lg-6 col-md-4 col-sm-4 col-6 d-flex">
                          <div class="go-stats__value text-right ml-auto">
                            <h6 class="go-stats__label mb-1">Order <a href="{{ route('admin.order.view', $po->id) }}" style="color:#0066c0" target="_blank">#{{ $po->id }}</a></h6>
                            <span class="go-stats__meta">{{ $po->address }} @if(!empty($po->notes))<br>Notes: {{ $po->notes }} @endif</span>
                          </div>
                        </div>
                      </li>
					           @endforeach
					           @endforeach
                    </ul>
                  </div>
                  <div class="card-footer border-top">
                    <div class="row">
                      <div class="col">
                        <select class="custom-select custom-select-sm" style="max-width: 130px;">
                          <option>Recent</option>
                        </select>
                      </div>
                      <div class="col text-right view-report">
                        <a href="{{ route('admin.orders.index') }}">All Orders &rarr;</a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
			         <div class="col-lg-3 col-sm-6 mb-4">
                <div class="card card-small">
                  <div class="card-header border-bottom">
                    <h5 class="m-0">Most Viewed</h5>
                    <div class="block-handle"></div>
                  </div>
                  <div class="card-body p-0" style="width: 100%; min-height: 450px;">
                    <ul class="list-group list-group-small list-group-flush">
					           @foreach($most_viewed_pro as $mvp)
                      <li class="list-group-item d-flex px-3">
                        <span class="text-semibold text-fiord-blue">{{ $mvp->name }}</span>
                        <span class="ml-auto text-right text-semibold text-reagent-gray">{{ $mvp->views }}</span>
                      </li>
					           @endforeach
                    </ul>
                  </div>
                  <div class="card-footer border-top">
                    <div class="row">
                      <div class="col">
                        <select class="custom-select custom-select-sm">
                          <option selected>This Week</option>
                          <option>This Month</option>
                          <option>This Year</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              @foreach([
                ['title' => 'Payment Country', 'items' => $paymentCountryStats],
                ['title' => 'Order Source', 'items' => $sourceStats],
                ['title' => 'Delivery Country', 'items' => $deliveryCountryStats],
                ['title' => 'Division', 'items' => $divisionStats],
                ['title' => 'District', 'items' => $districtStats],
                ['title' => 'Area', 'items' => $areaStats],
              ] as $stat)
                <div class="col-lg-2 col-md-4 col-sm-6 mb-4">
                  <div class="card card-small">
                    <div class="card-header border-bottom">
                      <h6 class="m-0">{{ $stat['title'] }}</h6>
                    </div>
                    <ul class="list-group list-group-small list-group-flush">
                      @forelse($stat['items'] as $item)
                        <li class="list-group-item d-flex px-3">
                          <span class="text-truncate mr-2">{{ $item->label }}</span>
                          <strong class="ml-auto">{{ $item->total }}</strong>
                        </li>
                      @empty
                        <li class="list-group-item text-muted">No data</li>
                      @endforelse
                    </ul>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
@section('page-script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
<script src="{{asset('backend/scripts/app/app-user-profile.1.3.1.min.js')}}"></script>
<script src="{{ asset('backend/scripts/app/app-analytics-overview.1.3.1.min.js') }}"></script>
<script src="{{ asset('backend/scripts/app/app-edit-user-profile.1.3.1.min.js') }}"></script>
<script src="{{ asset('backend/scripts/shards-dashboards.1.3.1.min.js') }}"></script>
@stop
