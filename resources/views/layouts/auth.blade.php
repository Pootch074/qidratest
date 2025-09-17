<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/icons/qidra-icon.png') }}">
    <title>{{ config('app.name', 'DSWD - Qidra') }}</title>
    @vite(['resources/js/app.js'])
    @yield('header')
    @livewireStyles
  </head>
  <body class="qidraBg ">
    <div class="container-fluid">
        
        @yield('content')
    </div>
    @yield('scripts')
    @livewireScripts
  </body>
</html>
