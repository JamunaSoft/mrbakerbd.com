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
                    <h3 class="page-title">Feedbacks</h3>
                </div>
            </div>
            <!-- End Page Header -->
            <!-- Feedback Table -->
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Reason</th>
                    <th>Message</th>
                    <th>Rating</th>
                </tr>
                </thead>
                <tbody>
                @php $i = $feedbacks->firstItem() @endphp
                @foreach($feedbacks as $feedback)
                    <tr>
                        <td> {{ $i++ }} </td>
                        <td> {{ $feedback->customer_name }} </td>
                        <td> {{ $feedback->email }} </td>
                        <td> {{ $feedback->phone ?? 'N/A' }} </td>
                        <td> {{ $feedback->reason }} </td>
                        <td> {{ $feedback->feedback_message }} </td>
                        <td> {{ $feedback->overall_rating }}/5 </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Showing {{ $feedbacks->firstItem() ?? 0 }}-{{ $feedbacks->lastItem() ?? 0 }} of {{ $feedbacks->total() }} feedbacks</small>
                {{ $feedbacks->links('pagination::bootstrap-4') }}
            </div>
            <!-- End Feedback Table -->
        </div>

        @include('backend.partials.footer')

    </main>

@endsection
