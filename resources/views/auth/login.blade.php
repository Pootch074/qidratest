@extends('layouts.auth')

@section('content')
<div id="auth-login" class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8 bg-gray-50">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8 space-y-6">
        
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">Welcome to Qidra</h2>
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
                    <img src="{{ asset('assets/icons/icon-email.png') }}" alt="Email" class="w-6 h-6">
                </span>
                <input type="email" name="email" id="email" autocomplete="email" required
                    placeholder="jdcruz@dswd.gov.ph"
                    class="block w-full h-14 pl-12 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none">
            </div>

            <!-- Password Input with Eye Toggle (Alpine) -->
<div class="relative" x-data="{ show: false }">
    <!-- Left Icon -->
    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        <img src="{{ asset('assets/icons/icon-password.png') }}" alt="Password" class="w-6 h-6">
    </span>

    <!-- Password Field -->
    <input :type="show ? 'text' : 'password'" name="password" id="password"
        autocomplete="current-password" required
        placeholder="********"
        class="block w-full h-14 pl-12 pr-12 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none">

    <!-- Eye Toggle Button -->
    <button type="button" @click="show = !show"
        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700 focus:outline-none">

        <!-- Closed eye (default) -->
        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-7 0-10-7-10-7a20.02 20.02 0 013.956-4.911M6.1 6.1A9.956 9.956 0 0112 5c7 0 10 7 10 7a19.998 19.998 0 01-3.96 4.906M6.1 6.1L18 18" />
        </svg>

        <!-- Open eye -->
        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
    </button>
</div>




            <!-- Terms Checkbox -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                <label for="terms" class="text-sm text-gray-700">
                    I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms and Conditions</a>.
                </label>
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full flex justify-center items-center h-14 px-6 rounded-xl bg-[#150e60] text-white font-semibold shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
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



