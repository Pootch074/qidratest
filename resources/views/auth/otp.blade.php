@extends('layouts.auth')
@section('content')
    <div>
        <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-6 space-y-6">
            <div class="text-center flex flex-col items-center">

                <div class="flex items-center mt-2">
                    <img x-show="show" src="{{ Vite::asset('resources/images/dswd-color.png') }}" class="w-30">
                    &nbsp;&nbsp;
                    <img x-show="show" src="{{ Vite::asset('resources/images/qidra-logo3.png') }}" class="w-30">
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    OTP Verification
                </p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('otp.verify.submit') }}">
                @csrf
                <div class="flex justify-center">
                    <div class="relative">
                        <label>OTP Code</label>
                        <input type="text" name="otp_code" required placeholder="000000"
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                        @error('otp_code')
                            <p class="text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex justify-center gap-4">
                    <button type="button" onclick="window.history.back()"
                        class="h-14 py-2 px-6 mt-4 rounded-xl bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition">
                        Cancel
                    </button>

                    <button type="submit"
                        class="h-14 py-2 px-6 mt-4 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700 transition">
                        Verify
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
