@extends('layouts.auth')
@section('content')

<div id="auth-login" class="qidraBg flex h-auto justify-center px-4 py-12 sm:px-6 lg:px-8 mt-20" style="background-image: url('{{ Vite::asset('resources/images/login-background.png') }}')">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 space-y-6 h-[60vh]">
        
        <!-- Header -->
        <div class="text-center flex flex-col items-center">
            <h2 class="text-2xl font-bold text-gray-900">Welcome to</h2>
            <img x-show="show" src="{{ Vite::asset('resources/images/qidra-logo.png') }}" class="w-50">
            <p class="mt-2 text-sm text-gray-500">
                Sign in to your account
            </p>
        </div>

        <!-- Display Errors -->
        @error('email')
            <div class="text-red-500 text-sm">{{ $message }}</div>
        @enderror

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        <form class="space-y-5" action="{{ route('authenticate') }}" method="POST">
            @csrf

            <!-- Email Input -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <img src="{{ Vite::asset('resources/images/icons/icon-email.png') }}" alt="Email" class="w-6 h-6">
                </span>
                <input type="email" name="email" id="email" autocomplete="email" required
                    placeholder="jdcruz@dswd.gov.ph"
                    class="block w-full h-14 pl-12 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] transition outline-none">
            </div>

            <!-- Password Input with Eye Toggle (Alpine) -->
            <div class="relative" x-data="{ show: false }">
                <!-- Left Icon -->
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <img src="{{ Vite::asset('resources/images/icons/icon-password.png') }}" alt="Password" class="w-6 h-6">
                </span>

                <!-- Password Field -->
                <input :type="show ? 'text' : 'password'" name="password" id="password"
                    autocomplete="current-password" required
                    placeholder="********"
                    class="block w-full h-14 pl-12 pr-12 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] transition outline-none">

                <!-- Eye Toggle Button -->
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 focus:outline-none">

                    <!-- Closed eye (default) -->
                    <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}" alt="Show Password" class="w-5 h-5">

                    <!-- Open eye -->
                    <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}" alt="Hide Password" class="w-5 h-5">
                </button>
            </div>

            <!-- Terms Checkbox -->
            <div class="flex items-center space-x-2 pl-2">
                <input type="checkbox" name="terms" id="terms" required class="w-4 h-4 text-indigo-600 rounded focus:ring-[#2e3192] border-gray-300">
                <label for="terms" class="text-sm text-gray-700">
                    I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms and Conditions</a>.
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center items-center h-14 px-6 rounded-xl bg-[#2e3192] text-white font-semibold shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-[#2e3192] transition">
                    Login
                </button>
            </div>
        </form>

        <!-- Help -->
        <div class="text-center text-sm text-gray-500 mt-4">
            Need Help? Email us at
            <a href="mailto:ictsupport.fo11@dswd.gov.ph" class="text-indigo-600 hover:underline">ictsupport.fo11@dswd.gov.ph</a>
        </div>

    </div>
</div>
@endsection
{{-- @vite('resources/css/app.css') --}}



