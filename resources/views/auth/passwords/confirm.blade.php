@extends('layouts.master')

@section('content')

        <main class="main-content col">
          <div class="main-content-container container-fluid px-4 my-auto h-100">
            <div class="row no-gutters h-100">
              <div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
                <div class="card">
                  <div class="card-body">
                    <img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('images/'. Cache::get('settings')->logo) }}" alt="Logo">
                    <h5 class="auth-form__title text-center mb-4">{{ Cache::get('settings')->name }}</h5>
                    <form method="POST" action="{{ route('password.confirm') }}">
                      @csrf
                      <div class="form-group">
                        <label for="password">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                      </div>
                      <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">{{ __('Confirm Password') }}</button>
                    </form>
                  </div>
                </div>
                <div class="auth-form__meta d-flex mt-4">
                  @if (Route::has('password.request'))
                    <a class="mx-auto" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </main>

@endsection
