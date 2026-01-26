@extends('layouts.auth')
@section('content')
    <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-8 space-y-6 mx-auto mt-20">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Registration OTP Verification</h2>
        <p class="text-center text-gray-600">Enter the 6-digit code sent to your email.</p>

        @if (isset($otpExpiresAt))
            <p id="otp-timer" class="text-center text-sm text-gray-600 mb-3">
                Your OTP will expire in
                <span class="font-semibold text-[#2e3192]">
                    10:00
                </span>

            </p>
        @endif

        <!-- Form -->
        <form action="{{ route('register.verify.otp') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="otp_code" maxlength="6" placeholder="Enter OTP"
                class="block w-full h-14 pl-4 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 text-center text-xl focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

            <div class="flex justify-between">
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline self-center">Cancel</a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700">
                    Submit
                </button>
            </div>
        </form>
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
