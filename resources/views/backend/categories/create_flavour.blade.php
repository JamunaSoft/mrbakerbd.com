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
                    <h3 class="page-title">Add Flavour</h3>
                </div>
                <div class="col-12 col-sm-6 d-flex align-items-center">
                    <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                        <a href="{{ route('admin.flavour.index') }}" class="btn btn-secondary" title="Back">
                            <i class="material-icons">arrow_back</i> Back
                        </a>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.flavour.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-8">
                        <div class="card card-small mb-3">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item p-3">
                                    <div class="form-group">
                                        <label for="name">{{ __('Name') }}</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name" required placeholder="Flavour Name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                                        @enderror
                                    </div>
                                </li>
                                <li class="list-group-item d-flex p-3">
                                    <button class="btn btn-accent ml-auto" type="submit">
                                        <i class="material-icons">save</i> Save
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @include('backend.partials.footer')
    </main>
@endsection
