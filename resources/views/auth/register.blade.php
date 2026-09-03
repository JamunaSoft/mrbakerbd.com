@extends('backend.layouts.master')

@section('content')

    <main class="main-content col">
        <div class="main-content-container container-fluid px-4 my-auto h-100">
            <div class="row no-gutters h-100">
                <div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
                    <div class="card">
                        <div class="card-body">
                            <img class="auth-form__logo d-table mx-auto mb-3" src="{{ asset('images/' . Cache::get('settings')->logo) }}" alt="Logo">
                            <h5 class="auth-form__title text-center mb-4">{{ Cache::get('settings')->name }}</h5>
                            <form method="POST" action="{{ route('register') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('E-Mail') }}</label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="E-Mail">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">{{ __('Phone') }}</label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                           name="phone" value="{{ old('phone')}}"
                                           required placeholder="Enter number">
                                    {{--<div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">+88</span>
                                        </div>
                                        <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror"
                                               name="phone" value="{{ old('phone') ? ltrim(old('phone'), '+88') : '' }}"
                                               required placeholder="Enter number without +88">

                                    </div>--}}
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                         <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm Password">
                                </div>
                                <button type="submit" class="btn btn-pill btn-accent d-table mx-auto">{{ __('REGISTER') }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="auth-form__meta d-flex mt-4">
                        <a class="mx-auto" href="{{ route('login') }}">{{ __('Already have an account? Login') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection
