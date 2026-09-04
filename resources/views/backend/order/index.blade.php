@extends('backend.layouts.master')

@section('content')

        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">

            @include('backend.partials.navbar')

          </div>

          <div class="main-content-container container-fluid px-4 mb-4">
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Management</span>
                <h3 class="page-title">Orders</h3>
              </div>
            </div>
             {{-- Transaction History Table  --}}
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Order #</th>
                  <th>Date</th>
                  <th>Delivery Address</th>
                  <th>Qty</th>
                  <th>Total</th>
                  <th>Payment</th>
                  <th>Status</th>
                  <th>Source</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = $orders->firstItem() @endphp

                @foreach($orders as $order)
                <tr>
                  <td> {{ $i++ }} </td>
                  <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" style="color:#0066c0" target="_blank">#{{ $order->id }}</a>
                  </td>
                  <td> {{ DateTime::createFromFormat('Y-m-d H:i:s', $order->created_at)->format(Cache::get('settings')->date_format) }} </td>
                  <td> {{ $order->name }} - {{ $order->phone }} <br> {{ $order->address }} </td>
                  <td> {{ $order->total_qty }} </td>
                  <td> {{ number_format($order->payable_amount,2) }} </td>
                  <td> {{ $order->payment_status ? 'Paid' : 'Unpaid' }} </td>
                  <td> {{ $order->status }} </td>
                  <td> {{ $order->source }} </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-info" title="Edit">
                        <i class="material-icons">&#xE254;</i>
                      </a>
                      <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-salmon delete" title="Delete">
                          <i class="material-icons">&#xE872;</i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <small class="text-muted">Showing {{ $orders->firstItem() ?? 0 }}-{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</small>
              {{ $orders->links('pagination::bootstrap-4') }}
            </div>
             {{-- End Transaction History Table  --}}
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
