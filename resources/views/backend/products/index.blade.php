@extends('backend.layouts.master')

@section('page-style')
<style>
    .products-card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,.04); }
    .products-toolbar { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; align-items: flex-end; }
    .products-search { flex: 1; min-width: 220px; }
    .products-toolbar label { display: block; font-weight: 500; margin-bottom: 6px; }
    #products-table { width: 100% !important; }
    #products-table td { vertical-align: middle; }
    #products-table th { white-space: nowrap; }
    #products-table th.sorting, #products-table th.sorting_asc, #products-table th.sorting_desc { cursor: pointer; }
    #products-table img { object-fit: contain; border-radius: 6px; }
    #products-table_wrapper .dataTables_length select { border: 1px solid #ddd; border-radius: 6px; padding: 6px; margin: 0 6px; }
    /* The theme floats DataTables controls at 50% width; keep this table stacked. */
    #products-table_wrapper { width: 100%; min-width: 0; box-shadow: none; }
    #products-table_wrapper .dataTables_length { float: none; width: 100%; padding: 0; }
    #products-table_wrapper .dataTables_length label { margin: 0; }
    #products-table_wrapper .products-table-scroll { clear: both; width: 100%; overflow-x: auto; margin-top: 12px; }
    #products-table_wrapper .dataTables_info,
    #products-table_wrapper .dataTables_paginate { float: none; width: auto; padding: 0; border: 0; background: transparent; }
    .products-table-footer { display: flex; flex-wrap: wrap; gap: 12px; justify-content: space-between; align-items: center; padding-top: 16px; }
    #products-table_wrapper .paginate_button { display: inline-block; padding: 6px 12px; margin: 2px; border: 1px solid #e1e5eb; border-radius: 6px; cursor: pointer; }
    #products-table_wrapper .paginate_button.current { background: #ffb400; color: #212529; border-color: #ffb400; }
    #products-table_wrapper .paginate_button.disabled { color: #adb5bd; cursor: default; }
    #products-table_wrapper .dataTables_empty { text-align: center; padding: 32px; }
</style>
@endsection

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
            <div class="products-card">
              <div class="products-toolbar">
                <div class="products-search">
                  <label for="product-search">Search products</label>
                  <input id="product-search" type="search" class="form-control" placeholder="Search by name, code or category…" aria-controls="products-table">
                </div>
                <div>
                  <label for="product-status">Status</label>
                  <select id="product-status" class="form-control" aria-controls="products-table">
                    <option value="">All statuses</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                  </select>
                </div>
                <button id="clear-product-filters" type="button" class="btn btn-outline-secondary">Clear filters</button>
              </div>
            <table id="products-table" class="table table-bordered table-hover">
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
                @php $i = 1 @endphp
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

                  <td data-order="{{ $product->type == 2 ? ($product->details->min('regular_price') ?? 0) : ($product->regular_price ?? 0) }}">
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
    $(function () {
        const table = $('#products-table').DataTable({
            pageLength: 25,
            lengthMenu: [10, 25, 50, 100],
            order: [],
            dom: 'l<"products-table-scroll"t><"products-table-footer"ip>',
            columnDefs: [
                { targets: [3, 9], orderable: false, searchable: false },
                { targets: [0], searchable: false }
            ],
            language: {
                lengthMenu: 'Show _MENU_ products',
                info: 'Showing _START_–_END_ of _TOTAL_ products',
                infoEmpty: 'No products to display',
                infoFiltered: '(filtered from _MAX_ products)',
                emptyTable: 'No products yet. Add your first product to get started.',
                zeroRecords: 'No matching products. Try another search or clear the filters.'
            }
        });

        $('#product-search').on('input', function () {
            table.search(this.value).draw();
        });
        $('#product-status').on('change', function () {
            table.column(8).search(this.value ? '^' + this.value + '$' : '', true, false).draw();
        });
        $('#clear-product-filters').on('click', function () {
            $('#product-search, #product-status').val('');
            table.search('').columns().search('').draw();
            $('#product-search').trigger('focus');
        });
    });

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
