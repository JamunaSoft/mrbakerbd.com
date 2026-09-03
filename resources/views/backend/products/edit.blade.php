@extends('backend.layouts.master')
@section('page-style')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .imagePreview
    {
      width:100%;
      height:200px;
      background-position:center center;
      background:url('{{ asset('images/products/placeholder.png') }}');
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
    .del
    {
      position:absolute;
      top:0px;
      right:15px;
      width:30px;
      height:30px;
      text-align:center;
      line-height:30px;
      background-color:rgba(208,208,208,0.7);
      cursor:pointer;
    }

    .imgAdd
    {
      width:30px;
      height:30px;
      border-radius:50%;
      background-color:#4bd7ef;
      color:#fff;
      box-shadow:0px 0px 2px 1px rgba(0,0,0,0.2);
      text-align:center;
      line-height:30px;
      margin-top:0px;
      margin-left:15px;
      cursor:pointer;
      font-size:15px;
    }
    .custom-drop-area {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        border: 2px dashed #0087F7;
        border-radius: 5px;
        background: #fafbfc;
        min-height: 120px;
        padding: 10px;
        gap: 10px;
        cursor: pointer;
    }
    .custom-drop-area * {
        cursor: pointer;
    }

    .gallery-message {
        width: 100%;
        text-align: center;
        color: #666;
        font-size: 1rem;
        cursor: pointer;
        padding: 10px 0;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 0;
        z-index: 1;
        pointer-events: none;
    }

    .gallery-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        width: 100%;
        justify-content: center;
        z-index: 2;
    }
    .gallery-thumb {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #eee;
        position: relative;
    }
    .remove-thumb {
        display: block;
        color: orange;
        background: none;
        border: none;
        cursor: pointer;
        margin: 0 auto;
        text-align: center;
    }

    .custom-drop-area.dragover {
        border-color: #0056b3;
        background: #e6f0fa;
    }
    .gallery-preview:not(:empty) + .gallery-message {
        display: none;
    }
</style>
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
                <h3 class="page-title">Edit Product</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" title="Back">
                    <i class="material-icons">arrow_back</i> Back
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.products.update', $product->id) }}" method="post" enctype="multipart/form-data">
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
                              <div class="form-group col-md-6">
                                <label for="name">{{ __('Product Name') }}</label>
                                <input class="form-control form-control-md mb-3 @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required placeholder="Product Name">
                                @error('name')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-6">
                                <label for="code">{{ __('Product Code') }}</label>
                                <input class="form-control form-control-md mb-3 @error('code') is-invalid @enderror" type="text" name="code" id="code" value="{{ old('code', $product->code) }}" placeholder="Product Code">
                                @error('code')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                            </div>
                            <div class="form-group">
                              <label for="short_desc">{{ __('Short Description') }}</label>
                              <input class="form-control form-control-md mb-3 @error('short_desc') is-invalid @enderror" type="text" name="short_desc" id="short_desc" value="{{ $product->short_desc }}" placeholder="Short Description">
                              @error('short_desc')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group">
                              <label for="description">{{ __('Description') }}</label>
                              <textarea name="description" id="description">{{ $product->description }}</textarea>
                              @error('description')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group">
                              <label for="type">{{ __('Product Type') }}</label>
                              <select class="custom-select mb-3" name="type" id="type">
                                <option value="1" {{ $product->type == 1 ? 'selected' : '' }}>Simple Product</option>
                                <option value="2" {{ $product->type == 2 ? 'selected' : '' }}>Variable Product</option>
                              </select>
                              @error('type')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>

                            <div class="form-row" id="simple_product" @if($product->type == 2) style="display:none" @endif>
                              <div class="form-group col-md-6">
                                <label for="regular_price">{{ __('Regular Price') }}</label>
                                <input class="form-control form-control-md mb-3 @error('regular_price') is-invalid @enderror" type="text" name="regular_price" id="regular_price" value="{{ $product->regular_price }}" placeholder="Regular Price" @if($product->type == 1) required @endif>
                                @error('regular_price')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-6">
                                <label for="special_price">{{ __('Special Price') }}</label>
                                <input class="form-control form-control-md mb-3 @error('special_price') is-invalid @enderror" type="text" name="special_price" id="special_price" value="{{ $product->special_price }}" placeholder="Special Price">
                                @error('special_price')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                            </div>
                            <div class="form-group" id="variable_product" @if($product->type == 1) style="display:none" @endif>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td align="left" valign="middle">Size</td>
                                        <td align="left" valign="middle">Regular Price</td>
                                        <td align="left" valign="middle">Special Price</td>
                                        <td align="center" valign="middle">Action</td>
                                    </tr>
                                    </thead>
                                    <tbody class="item-table">
                                    <tr>
                                        <td align="left" valign="middle">
                                            <input class="form-control" type="text" name="size[]" id="size" placeholder="Size">
                                        </td>
                                        <td align="left" valign="middle">
                                            <input class="form-control" type="text" name="v_regular_price[]" id="v_regular_price" placeholder="Regular Price">
                                        </td>
                                        <td align="left" valign="middle">
                                            <input class="form-control" type="text" name="v_special_price[]" id="v_special_price" placeholder="Special Price">
                                        </td>
                                        <td align="center" valign="middle">
                                            <span class="btn btn-success btn-sm add-item"><i class="fa fa-plus" style="cursor:pointer"></i></span>
                                        </td>
                                    </tr>
                                    @foreach($product->details as $pd)
                                        <tr>
                                            <td align="left" valign="middle">
                                                <input class="form-control" type="text" name="size[]" value="{{ $pd->size }}" />
                                            </td>
                                            <td align="left" valign="middle">
                                                <input class="form-control" type="text" name="v_regular_price[]" value="{{ $pd->regular_price }}" />
                                            </td>
                                            <td align="left" valign="middle">
                                                <input class="form-control" type="text" name="v_special_price[]" value="{{ $pd->special_price }}" />
                                            </td>
                                            <td align="center" valign="middle">
                                                <div class="form-group col-md-6">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="pd{{ $pd->id }}" name="pd_id[]" value="{{ $pd->id }}">
                                                        <label class="custom-control-label" for="pd{{ $pd->id }}">Delete</label>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group">
                              <label for="category_id">{{ __('Category') }}</label>
                              <select class="form-control selectpicker mb-3 @error('category_id') is-invalid @enderror" data-live-search="true" name="category_id" id="category_id" required>
                                <option value="">Select</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                  {{ $category->name }}
                                </option>
                                  @foreach($category->child as $child)
                                  <option value="{{ $child->id }}" {{ $product->category_id == $child->id ? 'selected' : '' }}>
                                    &nbsp; &#8627; {{ $child->name }}
                                  </option>
                                  @endforeach
                                @endforeach
                              </select>
                              @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-group">
                              <label for="tags">Tags</label>
                              <input class="form-control form-control-md mb-3 @error('tags') is-invalid @enderror" name="tags" id="tags" value="{{ $product->tags }}">
                              @error('tags')
                                <span class="invalid-feedback" role="alert">
                                  <strong>{{ $message }}</strong>
                                </span>
                              @enderror
                            </div>
                            <div class="form-row">
                              <div class="form-group col-md-3">
                                <label for="featured">{{ __('Featured') }}</label>
                                <select class="custom-select mb-3" name="featured" id="featured">
                                  <option value="0" {{ $product->featured == 0 ? 'selected' : '' }}>No</option>
                                  <option value="1" {{ $product->featured == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                                @error('featured')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-3">
                                <label for="availability">{{ __('Availability') }}</label>
                                <select class="custom-select mb-3" name="availability" id="availability">
                                  <option value="1" {{ $product->availability == 1 ? 'selected' : '' }}>Available</option>
                                  <option value="0" {{ $product->availability == 0 ? 'selected' : '' }}>Not Available</option>
                                </select>
                                @error('availability')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-3">
                                <label for="review">{{ __('Review') }}</label>
                                <select class="custom-select mb-3" name="review" id="review">
                                  <option value="0" {{ $product->review == 0 ? 'selected' : '' }}>Disable</option>
                                  <option value="1" {{ $product->review == 1 ? 'selected' : '' }}>Enable</option>
                                </select>
                                @error('review')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-3">
                                <label for="status">{{ __('Status') }}</label>
                                <select class="custom-select mb-3" name="status" id="status">
                                  <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Active</option>
                                  <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Inactive</option>
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
                                <input class="form-control form-control-md mb-3 @error('meta_description') is-invalid @enderror" type="text" name="meta_description" id="meta_description" value="{{ $product->meta_description }}" placeholder="Meta Description">
                                @error('meta_description')
                                  <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                  </span>
                                @enderror
                              </div>
                              <div class="form-group col-md-6">
                                <label for="meta_keywords">{{ __('Meta Keywords') }}</label>
                                <input class="form-control form-control-md mb-3 @error('meta_keywords') is-invalid @enderror" type="text" name="meta_keywords" id="meta_keywords" value="{{ $product->meta_keywords }}" placeholder="Meta Keywords">
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
              <div class="col-lg-4 col-md-4">
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom">
                    <span>Image</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item p-3">
                        <div class="row">
                          <div class="col">
                            <div class="row">
                              <div class="col-sm-12 imgUp">
                                <div id="main-image-drop-area" class="custom-drop-area">
                                  <input type="file" id="main-image-input" name="image" accept="image/*" style="display:none;">
                                  <div id="main-image-message" class="gallery-message" style="display: @if($product->image) none; @endif">Drop image here or click to upload</div>
                                  <div id="main-image-preview" class="gallery-preview">
                                    @if ($product->image)
                                      <div style="display: flex; flex-direction: column; align-items: center;">
                                        <img src="{{ asset($product->image->thumbnail_url) }}" class="gallery-thumb" id="main-image-existing" alt="{{ $product->image->alt }}" data-product-id="{{ $product->id }}">
                                       {{-- <button type="button" class="remove-thumb" id="remove-main-image">Remove file</button>--}}
                                      </div>
                                    @endif
                                  </div>
                                </div>
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
                    <span>Gallery</span>
                  </div>
                  <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item p-3">
                        <div class="row">
                          <div class="col">
                            <div class="form-group">
                                <label for="gallery">Gallery</label>
                                <div id="gallery-drop-area" class="custom-drop-area">
                                    <input type="file" id="gallery-input" name="v_image[]" multiple style="display:none;">
                                    <div id="gallery-message" class="gallery-message">Drop images here or click to upload</div>
                                    <div id="gallery-preview" class="gallery-preview">
                                        @foreach($product->images as $image)
                                            <div style="display: flex; flex-direction: column; align-items: center;">
                                                <img src="{{ asset($image->thumbnail_url) }}" class="gallery-thumb" alt="{{ $image->alt }}">
                                                <button type="button" class="remove-thumb" data-id="{{ $image->id }}">Remove file</button>
                                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    <input type="hidden" name="removed_images" id="removed-images">
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
<script src="{{ asset('backend/scripts/app/app-blog-new-post.1.3.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $('#description').summernote({
      tabsize: 2,
      height: 200
    });

    $('#type').on('change', function(event)
                              {
                                var type = $(this).val();

                                if(type == '1')
                                {
                                  $('#regular_price').prop('required', true);

                                  $('#simple_product').show();
                                  $('#variable_product').hide();
                                }

                                if(type == '2')
                                {
                                  $('#regular_price').removeAttr('required', true);

                                  $('#simple_product').hide();
                                  $('#variable_product').show();
                                }
                              });

    $('.add-item').on('click', function()
                              {
                                var size = $('#size').val();
                                var v_regular_price = $('#v_regular_price').val();
                                var v_special_price = $('#v_special_price').val();

                                if(size == ''){ $('#size').focus(); return false; }
                                if(v_regular_price == ''){ $('#v_regular_price').focus(); return false; }

                                var html = '<tr>';
                                    html += '<td align="left" valign="middle">';
                                    html += '<input class="form-control" type="text" name="size[]" value="' + size + '" />';
                                    html += '</td>';
                                    html += '<td align="left" valign="middle">';
                                    html += '<input class="form-control" type="text" name="v_regular_price[]" value="' + v_regular_price + '" />';
                                    html += '</td>';
                                    html += '<td align="left" valign="middle">';
                                    html += '<input class="form-control" type="text" name="v_special_price[]" value="' + v_special_price + '" />';
                                    html += '</td>';
                                    html += '<td align="center" valign="middle">';
                                    html += '<button class="btn btn-danger btn-sm item-delete"><i class="fa fa-trash" style="cursor:pointer"></i></button>';
                                    html += '</td>';
                                    html += '</tr>';

                                $('.item-table').append(html);

                                $('#size').val('');
                                $('#v_regular_price').val('');
                                $('#v_special_price').val('');
                              });

                              $('.item-table').on('click', '.item-delete', function (e)
                              {
                                var element = $(this).parents('tr');
                                element.remove();
                                e.preventDefault();
                              });

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

    // Main image drop area logic for edit page
    const mainDropArea = document.getElementById('main-image-drop-area');
    const mainFileInput = document.getElementById('main-image-input');
    const mainPreview = document.getElementById('main-image-preview');
    const mainMessage = document.getElementById('main-image-message');
    let mainImageFile = null;
    let hasExistingMainImage = !!document.getElementById('main-image-existing');

    [mainDropArea, mainMessage].forEach(el => el.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-thumb')) return;
        mainFileInput.click();
    }));

    mainFileInput.addEventListener('change', function(e) {
        if (mainFileInput.files && mainFileInput.files[0]) {
            mainImageFile = mainFileInput.files[0];
            hasExistingMainImage = false;
            updateMainImagePreview();
        }
    });

    mainDropArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        mainDropArea.classList.add('dragover');
    });
    mainDropArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        mainDropArea.classList.remove('dragover');
    });
    mainDropArea.addEventListener('drop', function(e) {
        e.preventDefault();
        mainDropArea.classList.remove('dragover');
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            mainFileInput.files = e.dataTransfer.files;
            mainImageFile = e.dataTransfer.files[0];
            hasExistingMainImage = false;
            updateMainImagePreview();
        }
    });

    function updateMainImagePreview() {
        mainPreview.innerHTML = '';
        if (mainImageFile) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.flexDirection = 'column';
                div.style.alignItems = 'center';
                div.innerHTML = `
                    <img src="${e.target.result}" class="gallery-thumb" />
                    <button type="button" class="remove-thumb" id="remove-main-image">Remove file</button>
                `;
                mainPreview.appendChild(div);
            };
            reader.readAsDataURL(mainImageFile);
            mainMessage.style.display = 'none';
        } else if (hasExistingMainImage) {
            // Restore the original image preview if it existed
            const origImg = document.getElementById('main-image-existing');
            if (origImg) {
                const div = document.createElement('div');
                div.style.display = 'flex';
                div.style.flexDirection = 'column';
                div.style.alignItems = 'center';
                div.innerHTML = `
                    <img src="${origImg.src}" class="gallery-thumb" id="main-image-existing" alt="${origImg.alt}" data-product-id="${origImg.dataset.productId}">
                    <button type="button" class="remove-thumb" id="remove-main-image">Remove file</button>
                `;
                mainPreview.appendChild(div);
                mainMessage.style.display = 'none';
            }
        } else {
            mainMessage.style.display = 'block';
        }
    }

    mainPreview.addEventListener('click', function(e) {
        if (e.target.id === 'remove-main-image') {
            // If editing and there is an existing image, delete via AJAX
            const mainImageExisting = document.getElementById('main-image-existing');
            if (mainImageExisting && mainImageExisting.dataset.productId) {
                if (confirm('Are you sure you want to delete this image?')) {
                    fetch(`{{ route('admin.products.image.delete', ['product' => $product->id]) }}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            mainImageFile = null;
                            mainFileInput.value = '';
                            hasExistingMainImage = false;
                            updateMainImagePreview();
                        } else {
                            alert('Failed to delete image: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to delete image. Please try again.');
                    });
                }
            } else {
                // If not an existing image, just clear the preview
                mainImageFile = null;
                mainFileInput.value = '';
                hasExistingMainImage = false;
                updateMainImagePreview();
            }
        }
    });
</script>
<script>
window.existingGalleryImages = [
@foreach($product->images as $image)
    {
        id: '{{ $image->id }}',
        src: '{{ asset($image->thumbnail_url) }}',
        alt: '{{ $image->alt }}'
    },
@endforeach
];
</script>
<script>
const dropArea = document.getElementById('gallery-drop-area');
const fileInput = document.getElementById('gallery-input');
const preview = document.getElementById('gallery-preview');
const message = document.getElementById('gallery-message');
const removedImagesInput = document.getElementById('removed-images');
let filesArray = [];
let removedImageIds = [];

// Hide message if there are existing images
if (preview.querySelectorAll('.gallery-thumb').length > 0) {
    message.style.display = 'none';
}

// Make the entire drop area clickable, but ignore clicks on remove-thumb buttons
[dropArea, message].forEach(el => el.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-thumb')) return;
    fileInput.click();
}));

fileInput.addEventListener('change', function(e) {
    addFiles([...e.target.files]);
});

dropArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropArea.classList.add('dragover');
});
dropArea.addEventListener('dragleave', function(e) {
    e.preventDefault();
    dropArea.classList.remove('dragover');
});
dropArea.addEventListener('drop', function(e) {
    e.preventDefault();
    dropArea.classList.remove('dragover');
    addFiles([...e.dataTransfer.files]);
});

function addFiles(files) {
    files.forEach(file => {
        if (!filesArray.some(f => f.name === file.name && f.size === file.size)) {
            filesArray.push(file);
        }
    });
    updatePreview();
    updateFileInput();
}

function updatePreview() {
    preview.innerHTML = '';

    // Render existing images that are not removed
    window.existingGalleryImages.forEach(function(imgObj) {
        if (!removedImageIds.includes(imgObj.id)) {
            const imgDiv = document.createElement('div');
            imgDiv.style.display = 'flex';
            imgDiv.style.flexDirection = 'column';
            imgDiv.style.alignItems = 'center';
            imgDiv.innerHTML = `
                <img src="${imgObj.src}" class="gallery-thumb" data-id="${imgObj.id}" alt="${imgObj.alt}">
                <button type="button" class="remove-thumb" data-id="${imgObj.id}">Remove file</button>
                <input type="hidden" name="existing_images[]" value="${imgObj.id}">
            `;
            preview.appendChild(imgDiv);
        }
    });

    // Render new files
    filesArray.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.style.display = 'flex';
            div.style.flexDirection = 'column';
            div.style.alignItems = 'center';
            div.innerHTML = `
                <img src="${e.target.result}" class="gallery-thumb" />
                <button type="button" class="remove-thumb" data-idx="${idx}">Remove file</button>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    // Hide message if there are files
    message.style.display = (document.querySelectorAll('input[name="existing_images[]"]').length - removedImageIds.length + filesArray.length) ? 'none' : 'block';
}

preview.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-thumb')) {
        const idx = e.target.getAttribute('data-idx');
        const imageId = e.target.getAttribute('data-id');

        if (imageId) {
            // This is an existing image - delete it from server
            if (confirm('Are you sure you want to delete this image?')) {
                fetch(`{{ route('admin.products.gallery.delete', ['image' => 'IMAGE_ID']) }}`.replace('IMAGE_ID', imageId), {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Mark as removed
                        removedImageIds.push(imageId);
                        // Remove the hidden input for this image
                        const hiddenInput = preview.querySelector('input[name="existing_images[]"][value="' + imageId + '"]');
                        if (hiddenInput) hiddenInput.remove();
                        updatePreview();
                    } else {
                        alert('Failed to delete image: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete image. Please try again.');
                });
            }
        } else if (idx !== null) {
            // This is a new file
            filesArray.splice(idx, 1);
            updatePreview();
            updateFileInput();
        }
    }
});

function updateFileInput() {
    // Create a new DataTransfer to update the file input
    const dt = new DataTransfer();
    filesArray.forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}
</script>
@endsection
