@extends('layouts.auth')
@section('content')
    <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-8 space-y-6 mx-auto mt-20">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Login OTP Verification</h2>
        <p class="text-center text-gray-600">Enter the 6-digit code sent to your email.</p>

        {{-- Check if there is a validation error for the "otp" field --}}
        @if ($errors->has('otp'))
            {{-- Display the first validation error message for "otp" --}}
            {{-- Styled in red text, small font, and centered --}}
            <div class="text-red-600 text-sm text-center">
                {{ $errors->first('otp') }}
            </div>
        @endif

        {{-- Check if there is a "success" message stored in the session --}}
        @if (session('success'))
            {{-- Display the success message --}}
            {{-- Styled with a green background, green border, rounded corners, --}}
            {{-- padding, small text, and centered alignment --}}
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('login.verify.otp') }}" method="POST" class="space-y-5">
            @csrf
            <input type="text" name="otp" maxlength="6" placeholder="Enter OTP"
                class="block w-full h-14 pl-4 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 text-center text-xl focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

            <div class="flex justify-between">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline self-center">Cancel</a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection
