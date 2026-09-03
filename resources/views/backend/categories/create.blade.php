@extends('backend.layouts.master')
@section('page-style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .imagePreview
    {
      width:100%;
      height:200px;
      background-position:center center;
      background:url('{{ asset('images/categories/placeholder.png') }}');
      background-color:#fff;
      background-size:cover;
      background-repeat:no-repeat;
      display:inline-block;
      box-shadow:0px 0px 6px 2px rgba(0,0,0,0.2);
    }
    .btn-white
    {
      display:block;
      border-radius:0px;
      box-shadow:0px 4px 6px 2px rgba(0,0,0,0.2);
      margin-top:-5px;
    }
  </style>
@stop
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
                <h3 class="page-title">Add Category</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" title="Back">
                    <i class="material-icons">arrow_back</i> Back
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-8 col-md-8">
                <div class="card card-small mb-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <div class="form-group">
                            <label for="name">{{ __('Name') }}</label>
                            <input class="form-control form-control-md mb-3 @error('name') is-invalid @enderror" type="text" name="name" id="name" required placeholder="Name">
                            @error('name')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>
                          <div class="form-group">
                            <label for="description">{{ __('Description') }}</label>
                            <textarea name="description" id="description"></textarea>
                            @error('description')
                              <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                              </span>
                            @enderror
                          </div>

                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="position">{{ __('Position') }}</label>
                              <input class="form-control form-control-md mb-3 @error('position') is-invalid @enderror" type="number" min="1" name="position" id="position" required placeholder="Position">
                              @error('position')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-4">
                              <label for="parent_id">{{ __('Parent') }}</label>
                              <select class="form-control selectpicker mb-3 @error('parent_id') is-invalid @enderror" data-live-search="true" name="parent_id" id="parent_id">
                                <option value="">Select</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">
                                  {{ $cat->name }}
                                </option>
                                @endforeach
                              </select>
                              @error('parent_id')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-3">
                              <label for="status">{{ __('Status') }}</label>
                              <select class="custom-select mb-3" name="status" id="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                              </select>
                              @error('status')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
{{--                              <div class="form-group col-md-1 d-flex align-items-center">--}}
{{--                                  <div class="form-check mt-4">--}}
{{--                                      <input class="form-check-input" type="checkbox" name="is_customized" id="is_custom" value="1">--}}
{{--                                      <label class="form-check-label" for="is_custom">{{ __('Custom') }}</label>--}}
{{--                                  </div>--}}
{{--                              </div>--}}
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="meta_description">{{ __('Meta Description') }}</label>
                              <input class="form-control form-control-md mb-3 @error('meta_description') is-invalid @enderror" type="text" name="meta_description" id="meta_description" placeholder="Meta Description">
                              @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6">
                              <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                              <input class="form-control form-control-md mb-3 @error('meta_keywords') is-invalid @enderror" type="text" name="meta_keywords" id="meta_keywords" placeholder="Meta Keywords">
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
                      <button class="btn btn-accent ml-auto" type="submit"><i class="material-icons">save</i> Save</button>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom">
                    <span>Icon</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item p-3">
                        <div class="row">
                          <div class="col">

                            <div class="row">
                              <div class="col-sm-12 imgUp">
                                <div class="imagePreview"></div>
                                <label class="btn btn-white">
                                  Upload
                                  <input type="file" class="uploadFile img" style="width:0px;height:0px;overflow:hidden;" name="icon" accept="image/*">
                                </label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom">
                    <span>Banner</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item p-3">
                        <div class="row">
                          <div class="col">
                            <div class="row">
                              <div class="col-sm-12 imgUp">
                                <div class="imagePreview"></div>
                                <label class="btn btn-white">
                                  Upload
                                  <input type="file" class="uploadFile img" style="width:0px;height:0px;overflow:hidden;" name="banner" accept="image/*">
                                </label>
                              </div>
                            </div>

                          </div>
                        </div>
                      </li>
                    </ul>
                  </div>
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
  $('#description').summernote({
    tabsize: 2,
    height: 200
  });
</script>
<script>
    $(function(){
        $(document).on("change", ".uploadFile", function()
        {
          var uploadFile = $(this);
          var files = !!this.files ? this.files : [];
          if (!files.length || !window.FileReader) return;

          if (/^image/.test( files[0].type)){
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);

            reader.onloadend = function(){
              uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url("+this.result+")");
            }
          }
        });
    });
  </script>
@stop
