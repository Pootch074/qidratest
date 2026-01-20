@extends('layouts.auth')
@section('content')
    <div id="auth-login">
        <div class="w-full max-w-md bg-[#f6f6f6] rounded-2xl shadow-lg p-6 space-y-6">
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
            <form id="registerForm" class="space-y-5" action="{{ route('register.store') }}" method="POST">
                @csrf
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                <!-- First Name & Last Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="text" name="firstName" required placeholder="First Name"
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                    </div>

                    <div class="relative">
                        <input type="text" name="lastName" required placeholder="Last Name"
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Area of Assignment -->
                    <div class="relative">
                        <select name="divisionId" id="office_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled {{ old('office_id') ? '' : 'selected' }}>Area of Assignment
                            </option>
                            @foreach ($areaOfAssignment as $id => $name)
                                <option value="{{ $id }}" {{ old('office_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section / Unit -->
                    <div class="relative">
                        <select name="sectionId" id="section_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none"
                            {{ old('office_id') ? '' : 'disabled' }}>
                            <option value="" disabled selected>Section/Unit</option>

                            @if (old('office_id'))
                                @php
                                    $sections = DB::table('sections')
                                        ->where('division_id', old('office_id'))
                                        ->orderBy('section_name')
                                        ->get();
                                @endphp
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}"
                                        {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->section_name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Position & Email Add-->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <select name="position" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled selected>Select Position</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <input type="email" name="email" autocomplete="email" required placeholder="Email Address"
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                        @error('email')
                            <p id="emailMessage" class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="Password"
                            class="block w-full h-14 pl-3 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                class="w-5 h-5">
                            <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                class="w-5 h-5">
                        </button>
                    </div>

                    <div class="relative" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                            placeholder="Confirm Password"
                            class="block w-full h-14 pl-3 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

                        <button type="button" @click="show = !show"
                            class="absolute inset-y-0 right-0 flex items-center pr-3">
                            <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}"
                                class="w-5 h-5">
                            <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}"
                                class="w-5 h-5">
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @error('password_confirmation')
                        <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
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
                        Already have an account? <a href="{{ route('login') }}"
                            class="text-indigo-600 hover:underline">Sign
                            in</a>
                    </label>
                </div>
            </form>

            <!-- Help -->
            <div class="text-center text-sm text-gray-500 mt-4">
                Need Help? Email us at
                <a href="mailto:ictsupport.fo11@dswd.gov.ph"
                    class="text-indigo-600 hover:underline">ictsupport.fo11@dswd.gov.ph</a>
            </div>

        </div>
    </div>
@endsection
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>

@section('scripts')
    <script>
        setTimeout(() => {
            document.querySelectorAll('.form-error').forEach(el => {
                el.remove();
            });
        }, 10000);
    </script>

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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const divisionSelect = document.getElementById('office_id');
            const sectionSelect = document.getElementById('section_id');

            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;

                // Disable Section while loading
                sectionSelect.disabled = true;
                sectionSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

                fetch(`/auth/sections/${divisionId}`)
                    .then(response => response.json())
                    .then(sections => {
                        sectionSelect.disabled = false;
                        sectionSelect.innerHTML =
                            '<option value="" disabled selected>Section/Unit</option>';

                        if (sections.length === 0) {
                            sectionSelect.innerHTML +=
                                '<option value="">No sections available</option>';
                            return;
                        }

                        sections.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section.id;
                            option.textContent = section.section_name;
                            sectionSelect.appendChild(option);
                        });
                    })
                    .catch(() => {
                        sectionSelect.disabled = false;
                        sectionSelect.innerHTML = '<option value="">Failed to load sections</option>';
                    });
            });
        });
    </script>
@endsection
{{-- @vite('resources/css/app.css') --}}
