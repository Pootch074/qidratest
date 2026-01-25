<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/icons/qidra-icon3.png') }}">
    <title>{{ config('app.name', 'DSWD - Qidra') }} - {{ strtoupper(auth()->user()->getUserTypeTextAttribute()) }}
    </title>
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
            (function() {
                const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const autoLogoutUrl = "{{ route('auto.logout') }}";
                const sessionCheckUrl = "{{ route('session.check') }}";
                const loginUrl = "{{ route('login') }}";

                /** ---------------- Inactivity Logout (30 minutes) ---------------- **/
                let logoutTimer;

                function resetTimer() {
                    clearTimeout(logoutTimer);
                    logoutTimer = setTimeout(autoLogoutFetch, 1800000); // 30 minutes
                }

                function autoLogoutFetch() {
                    fetch(autoLogoutUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({})
                    }).finally(() => window.location.replace(loginUrl));
                }

                ["mousemove", "mousedown", "click", "keypress", "scroll", "touchstart"].forEach(evt =>
                    window.addEventListener(evt, resetTimer)
                );
                resetTimer();


                /** ---------------- Instant Logout on Back Button ---------------- **/
                history.pushState(null, "", location.href);
                window.addEventListener("popstate", function() {
                    // Prevent back navigation
                    history.pushState(null, "", location.href);

                    // Logout immediately
                    fetch(autoLogoutUrl, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrf,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({})
                    });

                    // Redirect to login
                    window.location.replace(loginUrl);
                });


                /** ---------------- Auto Logout on Tab/Window Close (Never on Reload) ---------------- **/
                let isReloading = false;

                // Detect reload explicitly
                window.addEventListener("beforeunload", function(event) {
                    try {
                        // ✅ STEP 1: Mark that user may be reloading
                        sessionStorage.setItem("isReloading", "true");

                        // ✅ STEP 2: Wait for confirmation on next load
                        setTimeout(() => sessionStorage.removeItem("isReloading"), 5000);

                        // ✅ STEP 3: If it's not a reload, send logout signal
                        if (!isReloading) {
                            const fd = new FormData();
                            fd.append('_token', csrf);
                            navigator.sendBeacon(autoLogoutUrl, fd);
                        }
                    } catch (e) {}
                });

                // Detect actual reload after page loads again
                window.addEventListener("load", function() {
                    if (sessionStorage.getItem("isReloading")) {
                        isReloading = true; // ✅ confirmed reload
                        sessionStorage.removeItem("isReloading");
                    }
                });


                /** ---------------- Session Check (Handles Forward Cache / Back Button) ---------------- **/
                function checkSession() {
                    fetch(sessionCheckUrl, {
                            method: "GET",
                            credentials: "same-origin"
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.active) window.location.replace(loginUrl);
                        })
                        .catch(() => window.location.replace(loginUrl));
                }

                window.addEventListener("pageshow", checkSession);
                window.addEventListener("load", checkSession);
            })
            ();
        </script>
    @endauth
</body>

</html>
