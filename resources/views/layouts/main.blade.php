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
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const autoLogoutUrl = "{{ route('auto.logout') }}";
    const sessionCheckUrl = "{{ route('session.check') }}";
    const loginUrl = "{{ route('login') }}";

    /** ---------------- Inactivity Logout (15 minutes) ---------------- **/
    let logoutTimer;
    function resetTimer() {
        clearTimeout(logoutTimer);
        logoutTimer = setTimeout(autoLogoutFetch, 900000); // 15 minutes
    }
    function autoLogoutFetch() {
        fetch(autoLogoutUrl, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json" },
            body: JSON.stringify({})
        }).finally(() => window.location.replace(loginUrl));
    }
    ["mousemove","mousedown","click","keypress","scroll","touchstart"].forEach(evt => window.addEventListener(evt, resetTimer));
    resetTimer();

    /** ---------------- Instant Logout on Back Button ---------------- **/
    history.pushState(null, "", location.href);
    window.addEventListener("popstate", function () {
        // Push state again immediately to prevent going back
        history.pushState(null, "", location.href);

        // Immediately redirect (no delay or page flicker)
        fetch(autoLogoutUrl, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": csrf, "Content-Type": "application/json" },
            body: JSON.stringify({})
        });

        // Force instant redirect to login
        window.location.replace(loginUrl);
    });

    /** ---------------- Auto Logout on Tab/Window Close ---------------- **/
    window.addEventListener("beforeunload", function () {
        try {
            const fd = new FormData();
            fd.append('_token', csrf);
            navigator.sendBeacon(autoLogoutUrl, fd);
        } catch (e) {}
    });

    /** ---------------- Session Check (Handles Forward Cache) ---------------- **/
    function checkSession() {
        fetch(sessionCheckUrl, { method: "GET", credentials: "same-origin" })
            .then(res => res.json())
            .then(data => { if (!data.active) window.location.replace(loginUrl); })
            .catch(() => window.location.replace(loginUrl));
    }
    window.addEventListener("pageshow", checkSession);
    window.addEventListener("load", checkSession);
})();
</script>
@endauth




</body>
</html>
