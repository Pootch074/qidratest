<div id="sidebar" class="flex flex-col bg-[#2E3192] border-r border-[#2E3192]-300 flex-shrink-0 p-3 w-64 min-h-screen">
    <!-- Logo -->
    <a href="/" class="flex items-center mb-3 mx-auto pt-1">
        <img src="{{ Vite::asset('resources/images/dswd-sdca-white.png') }}" alt="DSWD - SDCA" class="h-12">
    </a>

    <div class="my-4"></div>

    <!-- Navigation -->
    <ul class="space-y-1">
        @include('layouts.sidebar.' . Str::lower(auth()->user()->getUserTypeName()))
    </ul>
</div>
