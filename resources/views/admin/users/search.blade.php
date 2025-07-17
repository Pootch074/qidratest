<div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-3">
    <!-- Search Box & Filter Button -->
    <div class="flex w-full md:w-1/2 gap-2">
        <!-- Search Box -->
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <img src="{{ asset('build/assets/icons/icon-search.png') }}" alt="Search" class="h-5 w-5">
            </span>
            <input type="text" placeholder="Search" id="table-search"
                class="pl-10 pr-4 py-2 w-full border rounded-lg border-[#CDCFD2] focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Filter Button -->
        <button class="flex items-center gap-2 px-4 py-2 border rounded-lg border-[#CDCFD2] bg-white hover:bg-gray-100">
            <img src="{{ asset('build/assets/icons/icon-filter.svg') }}" alt="Filter" class="h-5 w-5">
            <span class="text-gray-700 text-sm">User Type Filter</span>
        </button>
    </div>

    <!-- Add Item Button -->
    @include('admin.users.create')
</div>
