@extends('backend.layouts.master')

@section('content')

    <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
        <div class="main-navbar sticky-top bg-white">
            @include('backend.partials.navbar')
        </div>
        <div class="main-content-container container-fluid px-4 mb-4">
            <div class="page-header row no-gutters py-4">
                <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                    <span class="text-uppercase page-subtitle">Management</span>
                    <h3 class="page-title">Flavours</h3>
                </div>
                <div class="col-12 col-sm-6 d-flex align-items-center">
                    <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                        <a href="{{ route('admin.flavour.create') }}" class="btn btn-warning" title="Add">
                            <i class="material-icons">add</i> New Flavour
                        </a>
                    </div>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @php $i = 1 @endphp
                @foreach($flavours as $flavour)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $flavour->name }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <form action="{{ route('admin.flavour.destroy', $flavour->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this flavour?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-salmon delete" title="Delete">
                                        <i class="material-icons">&#xE872;</i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @include('backend.partials.footer')
    </main>

@endsection
