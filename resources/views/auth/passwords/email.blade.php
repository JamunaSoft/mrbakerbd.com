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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                      <div class="form-group mb-4">
                        <label for="email">{{ __('E-Mail') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="E-mail">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <small id="emailHelp" class="form-text text-muted text-center">You will receive an email with a unique token.</small>
                      </div>
                      <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">{{ __('Reset Password') }}</button>
                    </form>
                  </div>
                </div>
                <div class="auth-form__meta d-flex mt-4">
                  <a class="mx-auto" href="{{ route('login') }}">Take me back to login</a>
                </div>
              </div>
            </div>
          </div>
        </main>

@endsection
