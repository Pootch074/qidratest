<!doctype html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DSWD - Qidratest') }}</title>
    @vite(['resources/js/app.js'])
    @yield('header')
  </head>
<body class="">
    <img src="{{ asset('assets/images/background.png')}}" alt="" class="absolute top-0 left-0 w-full h-full object-cover -z-10">
    <div class="container-fluid">
        @yield('content')
    </div>
    @yield('scripts')
</body>

</html>
