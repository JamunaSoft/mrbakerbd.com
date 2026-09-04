@extends('backend.layouts.master')

@section('content')

    <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
        <div class="main-navbar sticky-top bg-white">
            <!-- Main Navbar -->

            @include('backend.partials.navbar')

            <!-- End Main Navbar -->
        </div>
        <div class="main-content-container container-fluid px-4 mb-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4">
                <div class="col-12 col-sm-6 text-center text-sm-left mb-4 mb-sm-0">
                    <span class="text-uppercase page-subtitle">Management</span>
                    <h3 class="page-title">Contacts</h3>
                </div>
            </div>
            <!-- End Page Header -->
            <!-- Contacts Table -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                </tr>
                </thead>
                <tbody>
                @php $i = $contacts->firstItem() @endphp
                @foreach($contacts as $contact)
                    <tr>
                        <td> {{ $i++ }} </td>
                        <td> {{ $contact->first_name }} </td>
                        <td> {{ $contact->last_name }} </td>
                        <td> {{ $contact->email }} </td>
                        <td> {{ $contact->subject ?? 'N/A' }} </td>
                        <td> {{ $contact->message }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Showing {{ $contacts->firstItem() ?? 0 }}-{{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} contacts</small>
                {{ $contacts->links('pagination::bootstrap-4') }}
            </div>
            <!-- End Contacts Table -->
        </div>

        @include('backend.partials.footer')

    </main>

@endsection
