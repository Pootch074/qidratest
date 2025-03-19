<!-- Header -->
<header class="bg-white text-[#202224] py-4 px-10 flex justify-between items-center">
    <h1 class="text-[32px] font-medium">
        @yield('title')
        <span class="text-white text-xs rounded-full border-[#CDCFD2] bg-[#CDCFD2] px-3 py-1 ml-2 align-middle font-light">{{ Str::title(auth()->user()->getUserTypeName()) }}</span>
    </h1>

    <!-- Profile Dropdown -->
    <div x-data="{ open: false }" class="relative">
        <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
            <img src="{{ auth()->user()->avatar }}" alt="Profile" class="w-8 h-8 rounded-full mr-5">
            <div class="hidden sm:block mr-5 text-left">
                <span class="block">{{ Str::upper(auth()->user()->first_name) }}</span>
                <small class="text-gray-400 block text-xs">{{ Str::title(auth()->user()->getUserTypeName()) }}</small>
            </div>
            <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g x-show="!open">
                    <path id="Vector"
                        d="M15 11L12 14L9 11M21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12Z"
                        stroke="#565656" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </g>
                <g x-show="open">
                    <path d="M9 13L12 10L15 13M21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21C16.9706 21 21 16.9706 21 12Z"
                        stroke="#565656" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </g>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" x-transition @click.outside="open = false"
            class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg">
            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
            <a href="{{ route('logout') }}" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Logout</a>
        </div>
    </div>
</header>
