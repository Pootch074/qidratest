<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/icons/qidra-icon.png') }}">
    <title>{{ config('app.name', 'DSWD - Qidra') }} - {{ strtoupper(auth()->user()->getUserTypeName()) }} </title>
    @vite(['resources/js/app.js'])

    @yield('header')
    @livewireStyles
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- ✅ Global app URL -->
    <script>
        window.appUrl = "{{ url('/') }}";
    </script>
    
    @yield('header') 
    
</head>

<body class="min-h-screen flex flex-col">

    <div class="flex items-center justify-between px-4 w-full h-[8vh] bg-[#2e3192] shadow">
        @include('layouts.inc.displayheader')
    </div>

    
    

    <div class="flex flex-1">
        @yield('content')
    </div>


    {{-- <div class="w-full h-[8vh] bg-[#150e60]"> --}}
    <div class="w-full h-[8vh] bg-[#2e3192]">
        @include('layouts.inc.displayfooter')
    </div>



    @yield('scripts')
    @stack('scripts')
    @livewireScripts
</body>
</html>
