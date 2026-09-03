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
              <div class="col-12 col-sm-12 text-center text-sm-left mb-4 mb-sm-0">
                <h3 class="page-title">Settings</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <form class="add-new-post" action="{{ route('admin.settings.update', $setting->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-8 col-md-8">
                <div class="card card-small mb-3">
                  <div class="card-body">
                      <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input class="form-control form-control-md mb-3 @error('name') is-invalid @enderror" type="text" name="name" value="{{ $setting->name }}" required placeholder="Name">
                        @error('name')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="logo">{{ __('Logo') }}</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input @error('logo') is-invalid @enderror" id="logo" name="logo">
                          <label class="custom-file-label" for="logo">Choose file...</label>
                        </div>
                        @error('logo')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="site_title">{{ __('Site Title') }}</label>
                        <input class="form-control form-control-md mb-3 @error('site_title') is-invalid @enderror" type="text" name="site_title" value="{{ $setting->site_title }}" placeholder="Site Title">
                        @error('site_title')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="site_logo">{{ __('Site Logo') }}</label>
                        <div class="custom-file">
                          <input type="file" class="custom-file-input @error('site_logo') is-invalid @enderror" id="site_logo" name="site_logo">
                          <label class="custom-file-label" for="site_logo">Choose file...</label>
                        </div>
                        @error('site_logo')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="phone">{{ __('Phone') }}</label>
                        <input class="form-control form-control-md mb-3 @error('phone') is-invalid @enderror" type="phone" name="phone" value="{{ $setting->phone }}" required placeholder="Phone">
                        @error('phone')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="email">{{ __('Email') }}</label>
                        <input class="form-control form-control-md mb-3 @error('email') is-invalid @enderror" type="email" name="email" value="{{ $setting->email }}" required placeholder="Email">
                        @error('email')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="address">{{ __('Address') }}</label>
                        <input class="form-control form-control-md mb-3 @error('address') is-invalid @enderror" type="text" name="address" value="{{ $setting->address }}" placeholder="Address">
                        @error('address')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="admin_url">{{ __('Admin URL') }}</label>
                          <input class="form-control form-control-md mb-3 @error('admin_url') is-invalid @enderror" type="text" name="admin_url" value="{{ $setting->admin_url }}" placeholder="Admin URL">
                          @error('admin_url')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                        <div class="form-group col-md-6">
                          <label for="site_url">{{ __('Site URL') }}</label>
                          <input class="form-control form-control-md mb-3 @error('site_url') is-invalid @enderror" type="text" name="site_url" value="{{ $setting->site_url }}" placeholder="Site URL">
                          @error('site_url')
                            <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                        </div>
                      </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-4">
                <div class="card card-small mb-3">
                  <div class="card-body">
                      <div class="form-group">
                        <label for="date_format">{{ __('Date Format') }}</label>
                        <select class="custom-select mb-3" name="date_format">
                          <option value="Y-m-d" {{ $setting->date_format == 'Y-m-d' ? 'selected' : '' }}>Year-Month-Day</option>
                          <option value="d-m-Y" {{ $setting->date_format == 'd-m-Y' ? 'selected' : '' }}>Day-Month-Year</option>
                          <option value="m-d-Y" {{ $setting->date_format == 'm-d-Y' ? 'selected' : '' }}>Month-Day-Year</option>

                          <option value="Y/m/d" {{ $setting->date_format == 'Y/m/d' ? 'selected' : '' }}>Year/Month/Day</option>
                          <option value="d/m/Y" {{ $setting->date_format == 'd/m/Y' ? 'selected' : '' }}>Day/Month/Year</option>
                          <option value="m/d/Y" {{ $setting->date_format == 'm/d/Y' ? 'selected' : '' }}>Month/Day/Year</option>

                          <option value="Y.m.d" {{ $setting->date_format == 'Y.m.d' ? 'selected' : '' }}>Year.Month.Day</option>
                          <option value="d.m.Y" {{ $setting->date_format == 'd.m.Y' ? 'selected' : '' }}>Day.Month.Year</option>
                          <option value="m.d.Y" {{ $setting->date_format == 'm.d.Y' ? 'selected' : '' }}>Month.Day.Year</option>
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="facebook_url">{{ __('Facebook URL') }}</label>
                        <input class="form-control form-control-md mb-3 @error('facebook_url') is-invalid @enderror" type="text" name="facebook_url" value="{{ $setting->facebook_url }}" placeholder="Facebook URL">
                        @error('facebook_url')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="twitter_url">{{ __('Twitter URL') }}</label>
                        <input class="form-control form-control-md mb-3 @error('twitter_url') is-invalid @enderror" type="text" name="twitter_url" value="{{ $setting->twitter_url }}" placeholder="Twitter URL">
                        @error('twitter_url')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="instagram_url">{{ __('Instagram URL') }}</label>
                        <input class="form-control form-control-md mb-3 @error('instagram_url') is-invalid @enderror" type="text" name="instagram_url" value="{{ $setting->instagram_url }}" placeholder="Instagram URL">
                        @error('instagram_url')
                          <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                      <button class="btn btn-sm btn-accent ml-auto" type="submit"><i class="material-icons">save</i> Update</button>
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
