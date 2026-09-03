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
                <h3 class="page-title">Slides</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.slides.create') }}" class="btn btn-warning" title="Add">
                    <i class="material-icons">add</i> New Slide
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
                  <th>Image</th>
                  <th>Text</th>
                  <th>URL</th>
                  <th>Open</th>
                  <th>Position</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = 1 @endphp
                @foreach($slides as $slide)
                <tr>
                  <td> {{ $i++ }} </td>
                  <td>
                    <img src="@if ($slide->image) {{ asset('images/slides/' . $slide->image) }} @else {{ asset('images/slides/placeholder.png') }} @endif" alt="Image" width="100">
                  </td>
                  <td> {{ $slide->text }} </td>
                  <td> {{ $slide->url }} </td>
                  <td> {{ $slide->new_window ? 'New Tab' : 'Self' }} </td>
                  <td> {{ $slide->position }} </td>
                  <td> {{ $slide->status ? 'Active' : 'Inactive' }} </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                      <a href="{{ route('admin.slides.edit', $slide->id) }}" class="btn btn-info" title="Edit">
                        <i class="material-icons">&#xE254;</i>
                      </a>
                      <a href="{{ route('admin.slide.delete', $slide->id) }}" class="btn btn-salmon delete" title="Delete">
                        <i class="material-icons">&#xE872;</i>
                      </a>
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
