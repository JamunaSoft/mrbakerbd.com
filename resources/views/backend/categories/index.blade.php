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
                <h3 class="page-title">Categories</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.categories.create') }}" class="btn btn-warning" title="Add">
                    <i class="material-icons">add</i> New Category
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Transaction History Table -->
            <table id="mydatatable" class="transaction-history d-none">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Icon</th>
                  <th>Position</th>
                  <th>Slug</th>
                  <th>Parent</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = 1 @endphp
                @foreach($categories as $category)
                <tr>
                  <td> {{ $i++ }} </td>
                  <td> {{ $category->name }} </td>
                  <td>
                    <img src="@if ($category->iconImage) {{ asset($category->iconImage->path . '/' . $category->iconImage->name) }} @else {{ asset('images/categories/placeholder.png') }} @endif" width="50">
                  </td>
                  <td> {{ $category->position }} </td>
                  <td> {{ $category->slug }} </td>
                  <td>
                    @if(!is_null($category->parent_id))
                      {{ $category->parent->name }}
                    @endif
                  </td>
                  <td> {{ $category->status ? 'Active' : 'Inactive' }} </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-info" title="Edit">
                        <i class="material-icons">&#xE254;</i>
                      </a>
                      <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
            <!-- End Transaction History Table -->
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
