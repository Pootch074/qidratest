<li class="mx-1 my-2">
    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('dashboard') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-dashboard.svg') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Dashboard</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Questionnaire</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Questionnaire" class="h-5 w-5 mr-2">
        <span class="text-sm">Assessment Management</span>
    </a>
    <ul class="ml-6 space-y-1">
        <li class="my-2">
            <a href="{{ route('assessments') }}" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('assessments') ? 'bg-[#DB0C16]' : '' }}">
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Cycle Management" class="h-5 w-5 mr-2">
                <span class="text-sm">Period Management</span>
            </a>
        </li>
        <li class="my-2">
            <a href="#" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Assessments" class="h-5 w-5 mr-2">
                <span class="text-sm">Assessments</span>
            </a>
        </li>
    </ul>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('rmt') }}" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('rmt') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-rmt.svg') }}" alt="Orders" class="h-5 w-5 mr-2">
        <span class="text-sm">RMT Management</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('lgu') }}" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('lgu') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-lgu.svg') }}" alt="Deadlines" class="h-5 w-5 mr-2">
        <span class="text-sm">LGU Profiling</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-result.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">Results</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('users') }}" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('users') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-user.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">User Management</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-report.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">Reports</span>
    </a>
</li>
