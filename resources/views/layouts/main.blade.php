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

    <!-- insert here the content of the @section('header') -->
    @yield('header') 
</head>

<body class="min-h-screen flex flex-col">

    <div class="flex items-center justify-between px-4 py-2 bg-[#2e3192] shadow">
        @include('layouts.inc.header')
    </div>
    
    

    <div class="flex flex-1">
            @yield('content')
    </div>


    <div class="w-full h-[8vh] bg-[#2e3192] shadow">
        @include('layouts.inc.footer')
    </div>



    @yield('scripts')
    @stack('scripts')
    @livewireScripts
    @auth
<script>
    (function () {
        let logoutTimer;

        // Reset inactivity timer
        function resetTimer() {
            clearTimeout(logoutTimer);
            logoutTimer = setTimeout(autoLogout, 900000); // 1 minute
        }

        // Perform logout request
        function autoLogout() {
            fetch("{{ route('auto.logout') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({})
            }).then(() => {
                window.location.href = "/"; // redirect to home/login
            });
        }

        // Listen for user activity
        ["mousemove", "mousedown", "click", "keypress", "scroll", "touchstart"].forEach(evt => {
            window.addEventListener(evt, resetTimer);
        });

        // Start timer on page load
        resetTimer();
    })();
</script>
@endauth

</body>
</html>
