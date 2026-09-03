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
                <h3 class="page-title">Users</h3>
              </div>
              <div class="col-12 col-sm-6 d-flex align-items-center">
                <div class="d-inline-flex mb-sm-0 mx-auto ml-sm-auto mr-sm-0" role="group" aria-label="Page actions">
                  <a href="{{ route('admin.users.create') }}" class="btn btn-warning" title="Add">
                    <i class="material-icons">add</i> New User
                  </a>
                </div>
              </div>
            </div>
            <!-- End Page Header -->
            <!-- Transaction History Table -->
            <table id="mydatatable" class="transaction-history d-none">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>Details</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Photo</th>
                  <th>Address</th>
                    <th>Role</th>
                  <th>Online</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $i = 1 @endphp
                @foreach($users as $user)
                <tr>
                  <td> {{ $i++ }} </td>
                  <td> {{ $user->name }} </td>
                  <td> {{ $user->details }} </td>
                  <td> {{ $user->phone }} </td>
                  <td> {{ $user->email }} </td>
                  <td><img src="@if ($user->photo) {{ asset($user->photo) }} @elseif (App\Helpers\GravatarHelper::validate_gravatar($user->email)) {{ App\Helpers\GravatarHelper::gravatar_image($user->email) }} @else {{ asset('images/avatars/default.png') }} @endif" alt="Avatar" width="50"></td>
                  <td> {{ $user->address }} </td>
                    <td>{{ $user->getRoleNames()->first() }}</td>
                  <td> {{ Carbon\Carbon::parse($user->last_online)->diffForhumans() }} </td>
                  <td> {{ $user->active ? 'Active' : 'Inactive' }} </td>
                  <td>
                      <div class="btn-group btn-group-sm" role="group" aria-label="Table row actions">
                          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-info" title="Edit">
                              <i class="material-icons">&#xE254;</i>
                          </a>
                          <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-salmon delete" title="Delete" onclick="return confirm('Are you sure?')">
                                  <i class="material-icons">&#xE872;</i>
                              </button>
                          </form>
                      </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            <!-- End Transaction History Table -->
          </div>

          @include('backend.partials.footer')

        </main>

@endsection
