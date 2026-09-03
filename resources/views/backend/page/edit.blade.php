@extends('backend.layouts.master')
@section('page-style')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection
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
                <h3 class="page-title">Edit Page</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary" title="Back">
                    <i class="material-icons">arrow_back</i> Back
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.pages.update', $page->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card card-small mb-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="title">{{ __('Title') }}</label>
                            <input class="form-control form-control-md mb-3 @error('title') is-invalid @enderror" type="text" name="title" id="title"  value="{{ $page->title }}" required placeholder="Title">
                            @error('title')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label for="content">{{ __('Content') }}</label>
                            <textarea name="content" id="content">{{ $page->content }}</textarea>
                            @error('content')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="position">{{ __('Position') }}</label>
                              <input class="form-control form-control-md mb-3 @error('position') is-invalid @enderror" type="number" min="1" name="position" id="position" value="{{ $page->position }}" required placeholder="Position">
                              @error('position')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6">
                              <label for="status">{{ __('Status') }}</label>
                              <select class="custom-select mb-3" name="status" id="status">
                                <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>Inactive</option>
                              </select>
                              @error('status')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="meta_description">{{ __('Meta Description') }}</label>
                              <input class="form-control form-control-md mb-3 @error('meta_description') is-invalid @enderror" type="text" name="meta_description" id="meta_description" value="{{ $page->meta_description }}" placeholder="Meta Description">
                              @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6">
                              <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                              <input class="form-control form-control-md mb-3 @error('meta_keywords') is-invalid @enderror" type="text" name="meta_keywords" id="meta_keywords" value="{{ $page->meta_keywords }}" placeholder="Meta Keywords">
                              @error('meta_keywords')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
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
@section('page-script')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $('#content').summernote({
            tabsize: 2,
            height: 200
        });
    </script>
@stop
