@extends('backend.layouts.master')

@section('content')

         <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->

            @include('backend.partials.navbar')

            <!-- End Main Navbar -->
          </div>
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Management</span>
                <h3 class="page-title">Edit Order</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary" title="Back">
                    <i class="material-icons">arrow_back</i> Back
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.orders.update', $order->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card card-small mb-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label>{{ __('Order #') }}</label>
                                <input class="form-control form-control-md mb-3" type="text" value="{{ $order->id }}" readonly>
                              </div>
                              <div class="form-group col-md-6">
                                <label>{{ __('Date') }}</label>
                                <input class="form-control form-control-md mb-3" type="text" value="{{ DateTime::createFromFormat('Y-m-d H:i:s', $order->created_at)->format(Cache::get('settings')->date_format) }}" readonly>
                              </div>
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-6">
                                <label for="status">{{ __('Status') }}</label>
                                <select class="custom-select mb-3" name="status" id="status">
                                  <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                  <option value="Processing" {{ $order->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                                  <option value="Complete" {{ $order->status == 'Complete' ? 'selected' : '' }}>Complete</option>
                                  <option value="Canceled" {{ $order->status == 'Canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                              </div>
                              <div class="form-group col-md-6">
                                <label for="send_email">{{ __('Send Email') }}</label>
                                <select class="custom-select mb-3" name="send_email">
                                  <option value="1">Yes</option>
                                  <option value="0">No</option>
                                </select>
                              </div>
                            </div>
                        </div>
                      </div>
                    </li>
                    <li class="list-group-item d-flex p-3">
                      <button class="btn btn-accent ml-auto" type="submit"><i class="material-icons">save</i> Update</button>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            </form>
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
