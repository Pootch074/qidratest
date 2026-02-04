<h1>
    Reset Password Blade View
</h1>

<form id="registerForm" class="space-y-5" action="{{ route('password.update') }}" method="POST">
    @csrf
    <div>
        <input type="text" name="token" value="{{ $token }}" hidden>
    </div>

    <!-- Position & Email Add-->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="relative">
            <input type="email" name="email" autocomplete="email" value="{{ old('email') }}" required
                placeholder="Email Address"
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

            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}" class="w-5 h-5">
                <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}" class="w-5 h-5">
            </button>
        </div>

        <div class="relative" x-data="{ show: false }">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                placeholder="Confirm Password"
                class="block w-full h-14 pl-3 pr-12 rounded-xl border border-gray-300 bg-gray-50 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] outline-none">

            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3">
                <img x-show="!show" src="{{ Vite::asset('resources/images/icons/eye-close.png') }}" class="w-5 h-5">
                <img x-show="show" src="{{ Vite::asset('resources/images/icons/eye-open.png') }}" class="w-5 h-5">
            </button>
        </div>
        @error('password')
            <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

        @error('password_confirmation')
            <p class="form-error text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Submit -->
    <button type="submit"
        class="w-full h-14 rounded-xl bg-[#2e3192] text-white font-semibold hover:bg-indigo-700 transition">
        Submit
    </button>
</form>
