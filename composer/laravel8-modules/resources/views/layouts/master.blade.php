{{-- @extends('home::layouts.master')

@section('content')
<h1>Hello World</h1>

<p>
  This view is loaded from module: {!! config('home.name') !!}
</p>
@endsection --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>@yield('title', 'Modular') | {{ env('APP_NAME') }}</title>

  <link rel="shortcut icon" href="{{ env('APP_URL') }}favicon.ico" type="image/x-icon">


  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Fonts -->
  {{--
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600;700&display=swap" rel="stylesheet"> --}}

  <!-- Styles -->
  {{--
  <link rel="stylesheet" href="/public/app/css/app.css"> --}}

  @section('head')
    @if (\View::exists('shared.master.head'))
      @include('shared.master.head')
    @endif
  @show


  <link rel="stylesheet" href="{{ asset('./public/app/style.css') }}">

  <link rel="stylesheet" href="{{ asset('./public/master/style.css') }}">

  @isset($module)
    <link rel="stylesheet" href="{{ asset('./modules/' . $module['name'] . '/Public/' . $module['framework'] . '/style.css') }}">
  @endisset

  @stack('styles')
</head>

<body class="bg-gray-100 dark:bg-gray-900">

  @yield('main')

  @sectionMissing('main')
    @section('header')
      @if (View::exists('shared.master.header'))
        @include('shared.master.header')
      @endif
    @show

    @yield('sidebar')

    <div class="wrapper-content" style="min-height: calc(100vh - @empty($config) 88px @else 152px @endif)"> 
        @yield('content')
    </div>

    @section('footer')
        @if (View::exists('shared.master.footer') && !($hideFooter ?? false))
            @include('shared.master.footer')
        @endif
    @show
  @endif

  <script crossorigin="anonymous" src="https://unpkg.com/axios"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/jquery"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/popper.js"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/bootstrap@4"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/lodash"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/holderjs"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/mockjs"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/moment"></script>
  <script crossorigin="anonymous" src="https://unpkg.com/masonry-layout"></script>

  <script crossorigin="anonymous" src="{{ asset('./public/app/script.js') }}"></script>

  <script crossorigin="anonymous" src="{{ asset('./public/master/script.js') }}"></script>

  @stack('scripts')

  @isset($module)
  <script crossorigin="anonymous" src="{{ asset('./modules/' . $module['name'] . '/Public/' . $module['framework'] . '/script.js') }}"></script>
@endisset
</body>

</html>
