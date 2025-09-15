<div x-data="{ open: false }" class="flex items-center justify-between w-full px-4">
    <img class="h-20 w-auto mt-3" src="{{ Vite::asset('resources/images/dswd-white.png') }}" alt="Logo">

    <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
        @if(auth()->user()->avatar)
            <img src="{{ auth()->user()->avatar }}" alt="Profile" class="w-8 h-8 rounded-full mr-5">
        @else
            
        @endif

        <div class="hidden sm:block mr-5 text-left">
            <span class="block text-white font-semibold text-2xl">
                {{ Str::upper(optional(auth()->user()->section)->section_name ?? 'NO SECTION') }}
            </span>
            
        </div>

        <div class="w-5 h-5" aria-hidden="true">
            <img
                x-show="!open"
                x-cloak
                src="{{ Vite::asset('resources/images/icons/caret-down.png') }}"
                alt=""
                class="w-5 h-5 object-contain"
            />
            <img
                x-show="open"
                x-cloak
                src="{{ Vite::asset('resources/images/icons/caret-up.png') }}"
                alt=""
                class="w-5 h-5 object-contain"
            />
        </div>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" x-transition @click.outside="open = false"
        class="absolute right-0 mt-20 rounded-md shadow-lg">
        <a href="{{ route('logout') }}" class="text-[#ee1c25] hover:text-white border border-[#ee1c25] hover:bg-[#ee1c25] focus:ring-1 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-[#ee1c25] dark:text-[#ee1c25] dark:hover:text-white dark:hover:bg-[#ee1c25] dark:focus:ring-gray-800">Logout</a>
    </div>
</div>