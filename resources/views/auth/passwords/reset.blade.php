@extends('backend.layouts.master')

@section('content')

        <main class="main-content col">
          <div class="main-content-container container-fluid px-4 my-auto h-100">
            <div class="row no-gutters h-100">
              <div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
                <div class="card">
                  <div class="card-body">
                    <img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('images/'. Cache::get('settings')->logo) }}" alt="Logo">
                    <h5 class="auth-form__title text-center mb-4">{{ Cache::get('settings')->name }}</h5>
                    <form method="POST" action="{{ route('password.update') }}">
                      @csrf
                      <input type="hidden" name="token" value="{{ $token }}">
                      <div class="form-group">
                        <label for="email">{{ __('E-Mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="E-Mail">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                      <div class="form-group">
                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Password">
                      </div>
                      <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">{{ __('Reset Password') }}</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>

@endsection
