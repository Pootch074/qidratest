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
                {{-- <input type="hidden" name="recaptcha_token" id="recaptcha_token"> --}}

                <!-- First Name & Last Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <input type="text" name="firstName" value="{{ old('firstName') }}" required
                            placeholder="First Name"
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
                        <select name="divisionId" id="division_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled {{ old('divisionId') ? '' : 'selected' }}>Area of Assignment
                            </option>

                            @foreach ($divisions as $dvsn)
                                <option value="{{ $dvsn->id }}" {{ old('divisionId') == $dvsn->id ? 'selected' : '' }}>
                                    {{ $dvsn->division_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Section / Unit -->
                    <div class="relative">
                        <select name="sectionId" id="section_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none"
                            {{ old('divisionId') ? '' : 'disabled' }}>
                            <option value="" disabled selected>Section/Office</option>

                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ old('sectionId') == $section->id ? 'selected' : '' }}>
                                    {{-- {{ $section->section_name }} --}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- Position & Email Add-->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative">
                        <select name="position" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled>
                                Select Position
                            </option>

                            @foreach ($positions as $pstn)
                                <option value="{{ $pstn->id }}">
                                    {{ $pstn->position_name }}
                                </option>
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

                <!-- Step, Window, Category -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="relative">
                        <select name="stepId" id="step_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none"
                            {{ old('sectionId') ? '' : 'disabled' }}>
                            <option value="" disabled selected>Step</option>

                            @foreach ($steps as $stp)
                                <option value="{{ $stp->id }}" {{ old('stepId') == $stp->id ? 'selected' : '' }}>
                                    {{-- {{ $stp->step_name }} --}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <select name="windowId" id="window_id" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none"
                            {{ old('stepId') ? '' : 'disabled' }}>
                            <option value="" disabled selected>Window</option>

                            @foreach ($windows as $wndw)
                                <option value="{{ $wndw->id }}" {{ old('windowId') == $wndw->id ? 'selected' : '' }}>
                                    {{ $wndw->window_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="relative">
                        <select name="category" required
                            class="block w-full h-14 pl-3 pr-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">
                            <option value="" disabled selected>Category</option>

                        </select>
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
{{-- <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script> --}}

@section('scripts')
    <script>
        setTimeout(() => {
            document.querySelectorAll('.form-error').forEach(el => {
                el.remove();
            });
        }, 10000);
    </script>

    {{-- <script>
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
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const divisionSelect = document.getElementById('division_id');
            const sectionSelect = document.getElementById('section_id');
            const stepSelect = document.getElementById('step_id');
            const windowSelect = document.getElementById('window_id');

            /**
             * Generic function to populate a dropdown via AJAX
             * @param {HTMLSelectElement} select - The select element to populate
             * @param {string} url - The API endpoint to fetch data
             * @param {string} placeholder - Placeholder text for default option
             * @param {string} textKey - Object key for option text
             */
            const populateDropdown = (select, url, placeholder, textKey) => {
                select.disabled = true;
                select.innerHTML = `<option value="" disabled selected>Loading...</option>`;

                fetch(url)
                    .then(res => res.json())
                    .then(items => {
                        select.disabled = false;
                        select.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;

                        if (!items.length) {
                            select.innerHTML +=
                                `<option value="">No ${placeholder.toLowerCase()} available</option>`;
                            return;
                        }

                        items.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = item[textKey];
                            select.appendChild(option);
                        });
                    })
                    .catch(() => {
                        select.disabled = false;
                        select.innerHTML =
                            `<option value="">Failed to load ${placeholder.toLowerCase()}</option>`;
                    });
            };

            // Division -> Section
            divisionSelect.addEventListener('change', function() {
                const divisionId = this.value;
                populateDropdown(sectionSelect, `/auth/sections/${divisionId}`, 'Section/Office',
                    'section_name');
            });

            // Section -> Step
            sectionSelect.addEventListener('change', function() {
                const sectionId = this.value;
                populateDropdown(stepSelect, `/auth/steps/${sectionId}`, 'Step', 'step_name');
            });

            stepSelect.addEventListener('change', function() {
                const stepId = this.value;
                populateDropdown(windowSelect, `/auth/windows/${stepId}`, 'Window', 'window_number');
            });
        });
    </script>
@endsection
{{-- @vite('resources/css/app.css') --}}
