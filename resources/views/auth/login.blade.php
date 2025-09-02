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

            <!-- Password Input -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <img src="{{ asset('assets/icons/icon-password.png') }}" alt="Password" class="w-6 h-6">
                </span>
                <input type="password" name="password" id="password" autocomplete="current-password" required
                    placeholder="********"
                    class="block w-full h-14 pl-12 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition outline-none">
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
