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

                    {{-- Error message --}}
                    <p id="emailError" class="text-red-600 text-sm mt-1 hidden"></p>
                </div>

                <div class="flex justify-between items-center">
                    <button type="button" onclick="window.location='{{ route('login') }}'"
                        class="text-indigo-600 hover:underline">
                        Cancel
                    </button>

                    <button type="submit" id="submitBtn"
                        class="px-6 py-3 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // prevent default submission

                const email = emailInput.value.trim();
                if (!email) return;

                // Disable button while checking
                submitBtn.disabled = true;
                submitBtn.textContent = 'Checking...';
                emailError.classList.add('hidden');

                fetch("{{ url('auth/check-email') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            email: email
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            // Email exists, proceed
                            form.submit();
                        } else {
                            emailError.textContent = data.message || 'This email is not registered.';
                            emailError.classList.remove('hidden');
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Submit';
                        }
                    })
                    .catch(err => {
                        // Handle server errors or 404
                        if (err.status === 404) {
                            emailError.textContent = 'Email is not registered.';
                        } else {
                            emailError.textContent = 'Failed to verify email. Please try again.';
                        }
                        emailError.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Submit';
                    });
            });
        });
    </script>
@endsection
