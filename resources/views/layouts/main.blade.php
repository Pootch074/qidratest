<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DSWD - SDCA') }} - RTM Portal</title>
    @vite(['resources/css/app.css', 'resources/css/custom.scss', 'resources/js/app.js'])
    @yield('header')
    <style>
    </style>
</head>

<body class="h-screen flex">

    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="flex flex-col flex-1">

        @include('layouts.inc.topnav')

        <!-- Content Area -->
        <main class="flex-1 p-6 bg-[#F5F6FA]">
            @yield('content')
        </main>

        <div x-data="{ show: localStorage.getItem('cookie_consent') !== 'accepted' }" x-show="show" x-transition:enter="transform translate-y-full ease-out duration-500"
            x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            class="fixed bottom-0 left-0 w-full bg-gray-900 text-white p-4 shadow-lg flex items-center justify-between">
            <p class="text-sm">We use cookies to improve your experience. By using our site, you accept our <a
                    href="#" class="underline">Privacy Policy</a>.</p>
            <button @click="localStorage.setItem('cookie_consent', 'accepted'); show = false"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Accept Cookies
            </button>
        </div>

    </div>

    <footer class="bg-black text-white p-4 w-full fixed bottom-0">
        <div class="container mx-auto flex justify-end space-x-4">
            <a href="#" class="hover:underline">Privacy Policy</a>
            <a href="#" class="hover:underline">Terms of Service</a>
            <a href="#" class="hover:underline">Contact Us</a>
        </div>
    </footer>

    @yield('script')

</body>
</html>
