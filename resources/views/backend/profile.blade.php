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
                <h3 class="page-title">Profile</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-sm-12 col-lg-12">
                <!-- User Details Card -->
                <div class="card card-small user-details mb-4">
                  <div class="card-header p-0">
                    <div class="user-details__bg">
                      <img src="{{ asset('images/background/up-user-details-background.jpg') }}" alt="User Details Background Image">
                    </div>
                  </div>
                  <div class="card-body p-0">
                    <div class="user-details__avatar mx-auto">
                      <img src="@if (Auth::user()->photo) {{ asset('images/users/' . Auth::user()->photo) }} @elseif (App\Helpers\GravatarHelper::validate_gravatar(Auth::user()->email)) {{ App\Helpers\GravatarHelper::gravatar_image(Auth::user()->email) }} @else {{ asset('images/avatars/default.png') }} @endif" alt="Avatar">
                    </div>
                    <h4 class="text-center m-0 mt-2">{{ Auth::user()->name }}</h4>
                    <p class="text-center text-light m-0 mb-2">{{ Auth::user()->details }}</p>
                    <div class="user-details__user-data border-top border-bottom p-4">
                      <div class="row mb-3">
                        <div class="col w-50">
                          <span>Phone</span>
                          <span>{{ Auth::user()->phone }}</span>
                        </div>
                        <div class="col w-50">
                          <span>Email</span>
                          <span>{{ Auth::user()->email }}</span>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col w-100">
                          <span>Address</span>
                          <span>{{ Auth::user()->address }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End User Details Card -->
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card card-small mb-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item px-3">
					  <form method="post" action="{{ route('admin.profile.update') }}">
						@csrf
						<div class="form-row">
						  <div class="form-group col-md-3">
							<input type="password" class="form-control" name="password" placeholder="Current Password">
						  </div>
						  <div class="form-group col-md-3">
							<input type="password" class="form-control" name="password_new" placeholder="New Password">
						  </div>
						  <div class="form-group col-md-3">
							<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm New Password">
						  </div>
						  <div class="form-group col-md-3">
							<button class="btn btn-md btn-accent ml-auto" type="submit">Change Password</button>
						  </div>
						</div>
					  </form>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
