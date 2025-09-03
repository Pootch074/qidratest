<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="icon" type="image/png" href="/dswd.png"> --}}
    <title>{{ config('app.name', 'DSWD - SDCA') }} - {{ strtoupper(auth()->user()->getUserTypeName()) }} </title>
    @vite(['resources/js/app.js'])
    @yield('header')
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- insert here the content of the @section('header') -->
    @yield('header') 
</head>

<body class="min-h-screen flex flex-col">

    <div class="flex items-center justify-between px-4 w-full h-[8vh] bg-gray-800 shadow">
        @include('layouts.inc.displayheader')
    </div>

    
    

    <div class="flex flex-1">
        @yield('content')
    </div>


    <div class="w-full h-[8vh]  bg-gray-800">
        @include('layouts.inc.footer')
    </div>



    @yield('scripts')
    @stack('scripts')
    @livewireScripts
</body>
</html>
