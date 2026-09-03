@extends('backend.layouts.master')

@section('content')

    <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
        <div class="main-navbar sticky-top bg-white">
            @include('backend.partials.navbar')
        </div>
        <div class="main-content-container container-fluid px-4">
            <div class="page-header row no-gutters py-4">
                <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                    <span class="text-uppercase page-subtitle">Management</span>
                    <h3 class="page-title">Edit User</h3>
                </div>
                <div class="col-12 col-sm-6 d-flex align-items-center">
                    <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" title="Back">
                            <i class="material-icons">arrow_back</i> Back
                        </a>
                    </div>
                </div>
            </div>
            <form class="edit-user-post" action="{{ route('admin.users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="card card-small mb-3">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">{{ __('Name') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('name') is-invalid @enderror"
                                           type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="details">{{ __('Details') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('details') is-invalid @enderror"
                                           type="text" name="details" value="{{ old('details', $user->details) }}" placeholder="Details">
                                    @error('details')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">{{ __('Phone') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('phone') is-invalid @enderror"
                                           type="tel" name="phone" value="{{ old('phone', $user->phone) }}" required placeholder="Phone">
                                    @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('email') is-invalid @enderror"
                                           type="email" name="email" value="{{ old('email', $user->email) }}" required placeholder="Email">
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('password') is-invalid @enderror"
                                           type="password" name="password" placeholder="Leave blank to keep current password">
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="photo">{{ __('Photo') }}</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" name="photo">
                                        <label class="custom-file-label" for="photo">Choose file...</label>
                                    </div>
                                    @if($user->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset($user->photo) }}" alt="Current Photo" width="50">
                                        </div>
                                    @endif
                                    @error('photo')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address">{{ __('Address') }}</label>
                                    <input class="form-control form-control-md mb-3 @error('address') is-invalid @enderror"
                                           type="text" name="address" value="{{ old('address', $user->address) }}" placeholder="Address">
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="role">{{ __('Role') }}</label>
                                    <select class="custom-select mb-3 @error('role') is-invalid @enderror" name="role" required>
                                        <option value="">Choose Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}"
                                                {{ (old('role', $user->getRoleNames()->first()) == $role->name) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="active">{{ __('Status') }}</label>
                                    <select class="custom-select mb-3" name="active">
                                        <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="form-group text-right">
                                    <button class="btn btn-sm btn-accent" type="submit"><i class="material-icons">save</i> Update</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('backend.partials.footer')
    </main>

@endsection
