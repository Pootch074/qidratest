@extends('layouts.auth')
@section('content')
    <div id="auth-login">
        <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-8 space-y-6">
            <div class="text-center flex flex-col items-center">
                <h2 class="text-2xl font-bold text-gray-900">Welcome to</h2>

                <div class="flex items-center mt-2">
                    <img x-show="show" src="{{ Vite::asset('resources/images/dswd-color.png') }}" class="w-30">
                    &nbsp;&nbsp;
                    <img x-show="show" src="{{ Vite::asset('resources/images/qidra-logo3.png') }}" class="w-30">
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    Create an account
                </p>
            </div>

            <!-- Form -->
            <form id="registerForm" class="space-y-5" action="{{ route('authenticate') }}" method="POST">
                @csrf
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <!-- First Name & Last Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- First Name -->
                    <div class="relative">
                        {{-- <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <img src="{{ Vite::asset('resources/images/icons/icon-user.png') }}" alt="First Name"
                                class="w-6 h-6">
                        </span> --}}
                        <input type="text" name="first_name" required placeholder="First Name"
                            class="block w-full h-14 pl-6 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                    </div>

                    <!-- Last Name -->
                    <div class="relative">
                        <input type="text" name="last_name" required placeholder="Last Name"
                            class="block w-full h-14 pl-6 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                    </div>
                </div>

                <!-- Position & Area of Assignment -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Position -->
                    <div class="relative">
                        <select name="position" required
                            class="block w-full h-14 pl-6 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled selected>Select Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Area of Assignment -->
                    <div class="relative">
                        <input type="text" name="area_assignment" required placeholder="Area of Assignment"
                            class="block w-full h-14 pl-6 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                    </div>
                </div>

                <!-- Email Address -->
                <div class="relative">
                    <input type="email" name="email" autocomplete="email" required placeholder="Email Address"
                        class="block w-full h-14 pl-6 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                </div>

                <!-- Password -->
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" required placeholder="Password"
                        class="block w-full h-14 pl-6 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}" class="w-5 h-5">
                        <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}" class="w-5 h-5">
                    </button>
                </div>

                <div class="flex items-center space-x-2 pl-2">
                    <input type="checkbox" name="terms" id="terms" required
                        class="w-4 h-4 text-indigo-600 rounded focus:ring-[#2e3192] border-gray-300">
                    <label for="terms" class="text-sm text-gray-700">
                        I agree to the <a href="#" class="text-indigo-600 hover:underline">Terms and Conditions</a>.
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full h-14 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700 transition">
                    Register
                </button>
                <div class="flex items-center space-x-2 pl-2">
                    <label for="terms" class="text-sm text-gray-700">
                        Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Sign
                            in</a>
                    </label>
                </div>
            </form>

            <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>

            <!-- Help -->
            <div class="text-center text-sm text-gray-500 mt-4">
                Need Help? Email us at
                <a href="mailto:ictsupport.fo11@dswd.gov.ph"
                    class="text-indigo-600 hover:underline">ictsupport.fo11@dswd.gov.ph</a>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        grecaptcha.ready(() => {
            grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {
                    action: 'login'
                })
                .then(token => {
                    const recaptchaInput = document.getElementById('recaptcha_token');
                    if (recaptchaInput) {
                        recaptchaInput.value = token;
                    } else {
                        console.error('recaptcha_token input not found!');
                    }
                });
        });
    </script>
@endsection
{{-- @vite('resources/css/app.css') --}}
