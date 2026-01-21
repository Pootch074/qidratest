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

            @if (isset($otpExpiresAt))
                <p id="otp-timer" class="text-center text-sm text-gray-600 mb-3">
                    Your OTP will expire in <span class="font-semibold text-[#2e3192]">10:00</span>
                </p>
            @endif
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
    @if (isset($otpExpiresAt))
        <script>
            const otpExpiresAt = {{ $otpExpiresAt }} * 1000; // convert to milliseconds
            const timerElement = document.getElementById('otp-timer');

            const countdown = setInterval(() => {
                const now = new Date().getTime();
                const distance = otpExpiresAt - now;

                if (distance <= 0) {
                    clearInterval(countdown);
                    timerElement.innerHTML =
                        '<span class="text-red-600 font-semibold">Your OTP has expired. Please request a new one.</span>';
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.querySelector('span').textContent =
                    `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }, 1000);
        </script>
    @endif
@endsection
