@extends('backend.layouts.master')

@section('content')

        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->

            @include('backend.partials.navbar')

            <!-- End Main Navbar -->
          </div>
          <div class="main-content-container container-fluid px-4 mb-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
              <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                <span class="text-uppercase page-subtitle">Management</span>
                <h3 class="page-title">Products</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.products.create') }}" class="btn btn-warning" title="Add">
                    <i class="material-icons">add</i> New Product
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Transaction History Table -->
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>Price</th>
                  <th>Featured</th>
                  <th>Availability</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = $products->firstItem() @endphp
                @foreach($products as $product)
                <tr>
                  <td> {{ $i++ }} </td>
                  <td> {{ $product->name }} </td>
                  <td> {{ $product->code }} </td>
                  <td>
                    @if ($product->image && file_exists(public_path($product->image->path . '/' . $product->image->name)))
                      <img src="{{ file_exists(public_path($product->image->path . '/thumbs/' . $product->image->name)) ? $product->image->optimized_thumbnail_url : $product->image->optimized_url }}" alt="{{ $product->image->alt ?: $product->name }}" width="100" height="70" loading="lazy" decoding="async">
                    @else
                      <img src="{{ asset('images/products/placeholder.png') }}" alt="Image" width="100">
                    @endif
                  </td>
                  <td> {{ $product->category ? $product->category->name : '' }}</td>

                  <td>
                    @if($product->type == 1)
                        {{ $product->regular_price }}
                    @endif
                    @if($product->type == 2)
                        {{ $product->details->min('regular_price') }}
                         &ndash;
                        {{ $product->details->max('regular_price') }}
                    @endif
                  </td>
                  <td> {{ $product->featured ? 'Yes' : 'No' }} </td>
                  <td> {{ $product->availability ? 'Available' : 'Not Available' }} </td>
                  <td> {{ $product->status ? 'Active' : 'Inactive' }} </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-info" title="Edit">
                        <i class="material-icons">&#xE254;</i>
                      </a>
                      <a href="{{ route('admin.products.copy', $product->id) }}" class="btn btn-java" title="Copy">
                        <i class="material-icons">content_copy</i>
                      </a>
                      <button type="button" class="btn btn-salmon delete" title="Delete" onclick="deleteProduct({{ $product->id }})">
                        <i class="material-icons">&#xE872;</i>
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
              <small class="text-muted">Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products</small>
              {{ $products->links('pagination::bootstrap-4') }}
            </div>
            <!-- End Transaction History Table -->
          </div>

          @include('backend.partials.footer')

        </main>

        <!-- Hidden Delete Form -->
        <form id="delete-form" action="" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>


@endsection

@section('page-script')
<script>
    function deleteProduct(id) {
        Swal.fire({
            title: 'Are you sure to delete this product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff2855',
            cancelButtonColor: '#5A6169',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                const form = document.getElementById('delete-form');
                form.action = "{{ url('admin/products') }}/" + id;
                form.submit();
            }
        });
    }
</script>
@stop
