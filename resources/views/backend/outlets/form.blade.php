@extends('backend.layouts.master')
@section('content')
<main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
    <div class="main-navbar sticky-top bg-white">@include('backend.partials.navbar')</div>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header py-4"><h3 class="page-title">{{ $outlet->exists ? 'Edit Outlet' : 'Add Outlet' }}</h3></div>
        <a href="{{ route('admin.outlets.index') }}" class="btn btn-secondary mb-3">Back to Outlets</a>
        @if($errors->any())
            <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        <form class="card card-small p-4 mb-4" method="POST" action="{{ $outlet->exists ? route('admin.outlets.update', $outlet) : route('admin.outlets.store') }}">
            @csrf
            @if($outlet->exists) @method('PUT') @endif
            <div class="form-group"><label for="name">Name</label><input class="form-control" id="name" name="name" value="{{ old('name', $outlet->name) }}" maxlength="255" required></div>
            <div class="form-group"><label for="address">Address</label><textarea class="form-control" id="address" name="address" rows="3" maxlength="5000" required>{{ old('address', $outlet->address) }}</textarea></div>
            <div class="form-group"><label for="phone">Contact number</label><input class="form-control" type="tel" id="phone" name="phone" value="{{ old('phone', $outlet->phone) }}" maxlength="100"></div>
            <div class="form-group"><label for="sort_order">Display order (lower numbers first)</label><input class="form-control" type="number" id="sort_order" name="sort_order" min="0" max="4294967295" value="{{ old('sort_order', $outlet->sort_order) }}" required></div>
            <div class="form-group"><label for="status">Status</label><select class="form-control" id="status" name="status"><option value="1" @selected(old('status', $outlet->status) == 1)>Active</option><option value="0" @selected(old('status', $outlet->status) == 0)>Inactive</option></select></div>
            <div><button class="btn btn-accent" type="submit">Save Outlet</button></div>
        </form>
    </div>
    @include('backend.partials.footer')
</main>
@endsection
