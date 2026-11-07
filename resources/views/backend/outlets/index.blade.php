@extends('backend.layouts.master')
@section('content')
<main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
    <div class="main-navbar sticky-top bg-white">@include('backend.partials.navbar')</div>
    <div class="main-content-container container-fluid px-4">
        <div class="page-header py-4"><h3 class="page-title">Outlets</h3></div>
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <a href="{{ route('admin.outlets.create') }}" class="btn btn-warning mb-3">Add Outlet</a>
        <div class="card card-small mb-4"><div class="table-responsive">
            <table class="table mb-0"><thead><tr><th>Order</th><th>Name</th><th>Address</th><th>Contact</th><th>Status</th><th>Actions</th></tr></thead><tbody>
                @forelse($outlets as $outlet)
                <tr>
                    <td>{{ $outlet->sort_order }}</td><td>{{ $outlet->name }}</td><td>{{ $outlet->address }}</td><td>{{ $outlet->phone }}</td><td>{{ $outlet->status ? 'Active' : 'Inactive' }}</td>
                    <td><a class="btn btn-info btn-sm" href="{{ route('admin.outlets.edit', $outlet) }}">Edit</a>
                        <form class="d-inline" method="POST" action="{{ route('admin.outlets.destroy', $outlet) }}" onsubmit="return confirm('Delete this outlet?');">@csrf @method('DELETE')<button class="btn btn-danger btn-sm" type="submit">Delete</button></form>
                    </td>
                </tr>
                @empty<tr><td colspan="6" class="text-center">No outlets found.</td></tr>@endforelse
            </tbody></table>
        </div></div>
        {{ $outlets->links('pagination::bootstrap-4') }}
    </div>
    @include('backend.partials.footer')
</main>
@endsection
