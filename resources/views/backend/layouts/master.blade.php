<!doctype html>
<html class="no-js h-100" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @if(!request()->is('admin', 'admin/*'))
      @include('tracking.head')
    @endif
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ Cache::get('settings')->name }}</title>
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('backend.layouts.styles')
  </head>
  <body class="h-100">
    @if(!request()->is('admin', 'admin/*'))
      @include('tracking.body')
    @endif
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->

        @if(Auth::check())

        @include('backend.partials.sidebar')

        @endif

        @yield('content')

      </div>
    </div>
    @include('backend.layouts.scripts')
    @if(!request()->is('admin', 'admin/*'))
      @include('tracking.consent')
    @endif
  </body>
</html>
