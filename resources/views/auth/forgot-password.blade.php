<h1>
    Forgot Password Blade View
</h1>

<div class="relative">
    <form id="loginForm" class="space-y-5" action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <img src="{{ Vite::asset('resources/images/icons/icon-email.png') }}" alt="Email" class="w-6 h-6">
            </span>
            <input type="email" name="email" id="email" autocomplete="email" required
                placeholder="jdcruz@dswd.gov.ph"
                class="block w-full h-14 pl-12 pr-4 rounded-xl border border-gray-300 bg-gray-50 text-gray-900 placeholder-gray-400 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] transition outline-none">
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center items-center h-14 px-6 rounded-xl bg-[#2e3192] text-white font-semibold shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-[#2e3192] transition">
                Submit
            </button>
        </div>
    </form>
</div>
