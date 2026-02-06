@extends('layouts.auth')
@section('content')
    <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-8 space-y-6 mx-auto mt-20">
        <h2 class="text-2xl font-bold text-gray-900 text-center">Registration OTP Verification</h2>
        <p class="text-center text-gray-600">Enter the 6-digit code sent to your email.</p>

        @if (isset($otpExpiresAt))
            <p id="otp-timer" class="text-center text-sm text-gray-600 mb-3">
                Your OTP will expire in
                <span class="font-semibold text-[#2e3192]">05:00</span>
            </p>
        @endif

        @if ($errors->has('otp_code'))
            <div class="text-red-600 text-sm text-center">{{ $errors->first('otp_code') }}</div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm text-center">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('register.verify.otp') }}" method="POST" class="space-y-3">
            @csrf
            <input type="text" name="otp_code" maxlength="6" placeholder="Enter OTP"
                class="block w-full h-14 pl-4 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 text-center text-xl focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

            <div class="flex justify-between items-center">
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline self-center">Cancel</a>
                <button type="submit"
                    class="px-6 py-3 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700">
                    Submit
                </button>
            </div>
        </form>

        <div class="text-center mt-2">
            <form action="{{ route('register.resend.otp') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:underline" id="resendBtn" disabled>
                    Resend OTP
                </button>
            </form>
        </div>
    </div>

    @if (isset($otpExpiresAt))
        <script>
            const otpExpiresAt = {{ $otpExpiresAt }} * 1000;
            const timerElement = document.getElementById('otp-timer');
            const timeSpan = timerElement.querySelector('span');
            const submitButton = document.querySelector('button[type="submit"]');
            const resendButton = document.getElementById('resendBtn');

            function updateTimer() {
                const now = Date.now();
                const remaining = otpExpiresAt - now;

                if (remaining <= 0) {
                    clearInterval(countdown);
                    timerElement.innerHTML =
                        '<span class="text-red-600 font-semibold">Your OTP has expired. Please request a new one.</span>';
                    submitButton.disabled = true;
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    resendButton.disabled = false;
                    return;
                }

                const minutes = Math.floor(remaining / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);

                timeSpan.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
            }

            updateTimer();
            const countdown = setInterval(updateTimer, 1000);
        </script>
    @endif
@endsection
