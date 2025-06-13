<div id="sidebar" class="flex flex-col bg-[#2E3192] border-r border-[#2E3192]-300 flex-shrink-0 p-3 w-72 h-screen">
    <!-- Logo -->
    <a href="/" class="flex items-center mb-3 mx-auto pt-1">
        <img src="{{ Vite::asset('resources/images/dswd-sdca-white.png') }}" alt="DSWD - SDCA" class="h-12">
    </a>

    <div class="my-4"></div>

    <!-- Navigation -->
    <ul x-data="{ isAnyOpen: false }" class="space-y-2 overflow-hidden transition-all duration-300"
        :class="{ 'shadow-lg bg-gray-800/30 p-2 rounded-lg': isAnyOpen }">
        @include('layouts.sidebar.' . Str::lower(auth()->user()->getUserTypeName()))
    </ul>

    <a href="#" id="sidebar-doc" class="rounded-2xl p-4 text-white flex flex-col mt-5 group block w-64 transition-colors
        duration-200 ease-in-out bg-[#060B28] hover:bg-[#DB0C16]
        absolute bottom-25">
        <img src="{{ Vite::asset('resources/assets/icons/icon-question.png') }}" alt="Settings" class="h-8 w-8 mb-4">
        <h3 class="font-medium text-sm mb-1">Need help?</h3>
        <p class="text-xs mb-5">Please check our docs</p>
        <span class="text-[10px] btn uppercase bg-[#060B28] px-6 py-3 rounded-md mx-auto transition-colors duration-200 ease-in-out group-hover:bg-[#DB0C16]">
        Documentation
    </span>
    </a>

</div>
