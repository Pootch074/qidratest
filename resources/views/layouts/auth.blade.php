<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DSWD - Qidra') }}</title>
    @vite(['resources/js/app.js'])
    @yield('header')
    @livewireStyles
  </head>
  <body>
    <div class="container-fluid">
        
        @yield('content')
    </div>
    @yield('scripts')
    @livewireScripts
  </body>
</html>
