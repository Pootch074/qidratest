@extends('layouts.auth')
@section('content')
    <div>
        <div class="w-full text-center max-w-5xl bg-[#f6f6f6] rounded-2xl shadow-lg p-8 space-y-6">
            <h1 class="mt-2 text-2xl font-bold text-gray-500">
                Enter your email address
            </h1>
            <form id="loginForm" class="space-y-5" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <img src="{{ Vite::asset('resources/images/icons/icon-email.png') }}" alt="Email" class="w-6 h-6">
                    </span>
                    <input type="email" name="email" id="email" autocomplete="email" required
                        placeholder="jdcruz@dswd.gov.ph"
                        class="block w-full h-14 pl-12 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] transition outline-none">
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="window.location='{{ route('login') }}'"
                        class="text-indigo-600 hover:underline">
                        Cancel
                    </button>

                    <button type="submit"
                        class="px-6 py-3 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
{{-- @vite('resources/css/app.css') --}}
