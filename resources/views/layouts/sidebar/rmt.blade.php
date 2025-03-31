<li class="mx-1 my-2">
    <a href="{{ route('dashboard') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('dashboard') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-dashboard.png') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Dashboard</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('profile') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('profile') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Assessment" class="h-5 w-5 mr-2">
        <span class="text-sm">Assessment Management</span>
    </a>
    <ul class="ml-6 space-y-1">
        <li class="my-2">
            <a href="{{ route('assessments') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('assessments') ? 'bg-[#DB0C16]' : '' }}">
                <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Administration and Organization" class="h-5 w-5 mr-2">
                <span class="text-sm">Administration and Organization</span>
            </a>
        </li>
        <li class="my-2">
            <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
                <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Program Management" class="h-5 w-5 mr-2">
                <span class="text-sm">Program Management</span>
            </a>
        </li>
        <li class="my-2">
            <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
                <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Institutional Mechanism" class="h-5 w-5 mr-2">
                <span class="text-sm">Institutional Mechanism</span>
            </a>
        </li>
    </ul>
</li>

<li class="mx-1 my-2">
    <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Orders" class="h-5 w-5 mr-2">
        <span class="text-sm">Orders</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('deadlines') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('deadlines') ? 'bg-gray-300' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Deadlines" class="h-5 w-5 mr-2">
        <span class="text-sm">Deadlines</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-assessment.png') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">Settings</span>
    </a>
</li>
