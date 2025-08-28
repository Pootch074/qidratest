@extends('layouts.auth')

@section('content')
    <div id="auth-login"
        class="mt-10 flex min-h-full flex-col justify-center px-6 py-12 lg:px-8 w-1/3 mx-auto bg-[#2E3192] rounded-[25px] text-white">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
            <h2 class="text-left text-[48px] font-bold tracking-tight">Login</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

            @error('email')
                <div class="text-red-300 text-sm">{{ $message }}</div>
            @enderror

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-6" action="{{ route('authenticate') }}" method="POST">
                @csrf

                <div class="relative mt-2">
                    <img src="{{ asset('assets/icons/icon-email.png') }}" alt="Email"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 w-9 h-9">

                    <input type="email" name="email" id="email" autocomplete="email" required
                        placeholder="jdcruz@dswd.gov.ph" aria-placeholder="jdcruz@dswd.gov.ph"
                        class="block w-full h-[60px] rounded-md bg-white pl-16 pr-3 text-base text-gray-900
                                outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400
                                focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div class="relative mt-2">
                    <img src="{{ asset('assets/icons/icon-password.png') }}" alt="Password"
                        class="absolute left-4 top-1/2 transform -translate-y-1/2 w-9 h-9">

                    <input type="password" name="password" id="password" autocomplete="password" required
                        placeholder="********" aria-placeholder="placeholder"
                        class="block w-full h-[60px] rounded-md bg-white pl-16 pr-3 text-base text-gray-900
                                outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400
                                focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div class="relative mt-2 flex items-center space-x-2">
                    <input type="checkbox" name="terms" id="terms" required class="w-5 h-5 rounded-md">
                    <label for="terms" class="text-sm">I agree to the <a href="#" class="text-[#FFCC00]">Terms and Conditions</a>.</label>
                </div>

                <div>
                    <button type="submit"
                        class="flex w-full justify-center items-center rounded-md bg-[#DB0C16] px-3 h-[60px] text-base font-semibold text-white shadow-xs hover:bg-[#DB0C16]/90 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#DB0C16]-600">
                        Login
                    </button>
                </div>
            </form>

            @if(true)
            <div class="flex items-center my-5">
                <hr class="flex-grow border-t border-white" />
                <span class="px-3 text-white text-sm">or</span>
                <hr class="flex-grow border-t border-white" />
            </div>

            <a href="{{ url('/auth/redirect') }}"
                class="flex w-full justify-center items-center gap-2 rounded-md bg-white px-3 h-[60px] text-base font-semibold text-black shadow-xs border border-gray-300 hover:bg-gray-100 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-400">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" class="h-5 w-5">
                Google
            </a>
            @endif

            <div class="mt-20">
                Need Help? Email us at <a class="text-[#FFCC00]"
                    href="mailto:ictsupport.fo11@dswd.gov.ph">ictsupport.fo11@dswd.gov.ph</a>
            </div>

        </div>
    </div>
@endsection
