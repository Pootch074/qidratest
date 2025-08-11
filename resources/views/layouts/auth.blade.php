<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DSWD - SDCA') }}</title>
    @vite(['resources/js/app.js'])
    @yield('header')
  </head>
  <body>
    <div class="container-fluid">
        <div class="m-5">
            <img src="{{ asset('assets/images/dswd-sdca.png') }}" alt="DSWD - SDCA">
        </div>
        @yield('content')
    </div>
    @yield('scripts')
  </body>
</html>
