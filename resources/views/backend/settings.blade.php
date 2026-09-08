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
            @if(session('success'))
              <div class="alert alert-success" role="alert">{{ session('success') }}</div>
            @endif
            <form class="add-new-post" action="{{ route('admin.settings.update', $setting->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-lg-8 col-md-8">
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom"><h6 class="m-0">Google Tag Manager</h6></div>
                  <div class="card-body">
                    <div class="form-group mb-0">
                      <label for="google_tag_manager_id">Container ID</label>
                      <input id="google_tag_manager_id" class="form-control @error('google_tag_manager_id') is-invalid @enderror" type="text" name="google_tag_manager_id" value="{{ old('google_tag_manager_id', $setting->google_tag_manager_id) }}" placeholder="GTM-XXXXXXXX" maxlength="64" aria-describedby="gtm-help">
                      @error('google_tag_manager_id')
                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                      @enderror
                      <small id="gtm-help" class="form-text text-muted">Enter only your GTM container ID (for example, GTM-XXXXXXXX). The head and body codes are added automatically to all storefront pages. Leave blank and save to disable Google Tag Manager.</small>
                    </div>
                  </div>
                </div>
                <div class="card card-small mb-3">
                  <div class="card-header border-bottom"><h6 class="m-0">Analytics &amp; Advertising</h6></div>
                  <div class="card-body">
                    <div class="form-group">
                      <label for="tracking_delivery">Send tracking through</label>
                      <select id="tracking_delivery" name="tracking_delivery" class="form-control">
                        <option value="website" @selected(old('tracking_delivery', $setting->tracking_delivery ?? 'website') === 'website')>Website (works immediately after IDs are saved)</option>
                        <option value="gtm" @selected(old('tracking_delivery', $setting->tracking_delivery) === 'gtm')>Google Tag Manager (import and publish the setup first)</option>
                      </select>
                      @error('tracking_delivery')<span class="text-danger">{{ $message }}</span>@enderror
                      <small class="form-text text-muted">Use one delivery method to avoid duplicate conversions. <a href="{{ asset('tracking/gtm-container.json') }}" download>Download GTM setup</a>. Import it into GTM, Preview, then Publish before choosing Google Tag Manager.</small>
                    </div>
                    <div class="form-group">
                      <label for="ga4_measurement_id">GA4 Measurement ID</label>
                      <input id="ga4_measurement_id" name="ga4_measurement_id" class="form-control @error('ga4_measurement_id') is-invalid @enderror" value="{{ old('ga4_measurement_id', $setting->ga4_measurement_id) }}" placeholder="G-XXXXXXXXXX">
                      @error('ga4_measurement_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                      <label for="google_ads_id">Google Ads Conversion ID</label>
                      <input id="google_ads_id" name="google_ads_id" class="form-control @error('google_ads_id') is-invalid @enderror" value="{{ old('google_ads_id', $setting->google_ads_id) }}" placeholder="AW-123456789">
                      @error('google_ads_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                      <label for="google_ads_conversion_label">Google Ads Conversion Label</label>
                      <input id="google_ads_conversion_label" name="google_ads_conversion_label" class="form-control @error('google_ads_conversion_label') is-invalid @enderror" value="{{ old('google_ads_conversion_label', $setting->google_ads_conversion_label) }}" placeholder="Your conversion label">
                      @error('google_ads_conversion_label')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                      <label for="meta_pixel_ids">Meta Pixel IDs (comma separated)</label>
                      <input id="meta_pixel_ids" name="meta_pixel_ids" class="form-control @error('meta_pixel_ids') is-invalid @enderror" value="{{ old('meta_pixel_ids', $setting->meta_pixel_ids) }}" placeholder="123456789012345">
                      @error('meta_pixel_ids')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input" name="enhanced_conversions_enabled" id="enhanced_conversions_enabled" value="1" @checked(old('enhanced_conversions_enabled', $errors->any() ? false : $setting->enhanced_conversions_enabled))>
                      <label class="form-check-label" for="enhanced_conversions_enabled">Enable Google Ads Enhanced Conversions</label>
                      <small class="form-text text-muted">Enable Enhanced Conversions in Google Ads first. Hashed email and phone are sent only with accepted optional cookies. Contact data is not sent to GA4.</small>
                    </div>
                    <p class="mt-3 mb-0 text-muted">Purchase tracking includes placed Cash on Delivery orders and verified online payments. Failed online payments are excluded. Clear an ID to stop sending data to that destination.</p>
                  </div>
                </div>
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
