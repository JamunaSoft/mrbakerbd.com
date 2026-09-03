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
                <h3 class="page-title">Edit Slide</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.slides.index') }}" class="btn btn-secondary" title="Back">
                    <i class="material-icons">arrow_back</i> Back
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.slides.update', $slide->id) }}" method="post" enctype="multipart/form-data">
            @csrf
                @method('PUT')
            <div class="row">
              <div class="col-lg-8 col-md-8">
                <div class="card card-small mb-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col">
                          <div class="form-row">
                            <div class="form-group col-md-4">
                              <label for="text">{{ __('Button Text') }}</label>
                              <input class="form-control form-control-md mb-3 @error('text') is-invalid @enderror" type="text" name="text" id="text" value="{{ $slide->text }}" placeholder="Button Text">
                              @error('text')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-4">
                              <label for="url">{{ __('URL') }}</label>
                              <input class="form-control form-control-md mb-3 @error('url') is-invalid @enderror" type="text" name="url" id="url" value="{{ $slide->url }}" placeholder="URL">
                              @error('url')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-4">
                              <label for="new_window">{{ __('Open') }}</label>
                              <select class="custom-select mb-3" name="new_window" id="new_window">
                                <option value="1" {{ $slide->new_window == 1 ? 'selected' : '' }}>New Tab</option>
                                <option value="0" {{ $slide->new_window == 0 ? 'selected' : '' }}>Self</option>
                              </select>
                              @error('new_window')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                          </div>
                          <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="position">{{ __('Position') }}</label>
                              <input class="form-control form-control-md mb-3 @error('position') is-invalid @enderror" type="number" min="1" name="position" id="position" value="{{ $slide->position }}" required placeholder="Position">
                              @error('position')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group col-md-6">
                              <label for="status">{{ __('Status') }}</label>
                              <select class="custom-select mb-3" name="status" id="status">
                                <option value="1" {{ $slide->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $slide->status == 0 ? 'selected' : '' }}>Inactive</option>
                              </select>
                              @error('status')
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
              <div class="col-lg-4 col-md-4">
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom">
                    <span>Image (1920 X 500)</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item p-3">
                        <div class="row">
                          <div class="col">
                            <style>
                              .imagePreview
                              {
                                width:100%;
                                height:160px;
                                background-position:center center;
                                background:url('{{ asset('images/slides/placeholder.png') }}');
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
                            <div class="row">
                              <div class="col-sm-12 imgUp">
                                <div class="imagePreview" style="background-image:url(@if ($slide->image) {{ asset('images/slides/' . $slide->image) }} @else {{ asset('images/slides/placeholder.png') }} @endif);"></div>
                                <label class="btn btn-white">
                                  Upload
                                  <input type="file" class="uploadFile img" style="width:0px;height:0px;overflow:hidden;" name="image" accept="image/*">
                                </label>
                              </div>
                            </div>
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
