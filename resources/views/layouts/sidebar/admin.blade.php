<li class="mx-1 my-2">
    <a href="{{ route('dashboard') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('dashboard') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-dashboard.svg') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Dashboard</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('questionnaires') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('questionnaires') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Questionnaire</span>
    </a>
</li>

@php
    $isAssessmentSectionActive = Request::is('period-management') || Request::is('period-assessments');
@endphp

<li x-data="{ isOpen: {{ $isAssessmentSectionActive ? 'true' : 'false' }} }" class="mx-1 my-2 relative">
    <a href="#"
       class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]"
       :class="{ 'bg-[#851E54]': isOpen }"
       @click.prevent="isOpen = !isOpen">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Assessment Management" class="h-5 w-5 mr-2">
        <span class="text-sm">Assessment Management</span>
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle"
             class="h-5 w-5 mr-2 absolute right-1 transition-transform duration-300"
             :class="{ 'rotate-180': isOpen }">
    </a>

    <ul x-show="isOpen"
        x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition-all duration-200 ease-in"
        x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0"
        class="ml-6 space-y-1 overflow-hidden">

        <li class="my-2">
            <a href="{{ route('period-management') }}"
               class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('period-management') ? 'bg-[#DB0C16]' : '' }}">
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Cycle Management" class="h-5 w-5 mr-2">
                <span class="text-sm">Period Management</span>
            </a>
        </li>

        <li class="my-2">
            <a href="{{ route('period-assessments') }}"
               class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('period-assessments') ? 'bg-[#DB0C16]' : '' }}">
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Assessments" class="h-5 w-5 mr-2">
                <span class="text-sm">Assessments</span>
            </a>
        </li>
    </ul>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('rmt') }}"
       class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('rmt') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-rmt.svg') }}" alt="Orders" class="h-5 w-5 mr-2">
        <span class="text-sm">RMT Management</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('lgu') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('lgu') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-lgu.svg') }}" alt="Deadlines" class="h-5 w-5 mr-2">
        <span class="text-sm">LGU Profiling</span>
    </a>
</li>

{{-- <li class="mx-1 my-2">
    <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">Results</span>
    </a>
</li> --}}

{{-- <li class="mx-1 my-2">
    <a href="{{ route('reports') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('reports') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-report.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">Reports</span>
    </a>
</li> --}}
@php
    $isParameterSectionActive = Request::is('parameter-report') || Request::is('compliance-monitoring');
@endphp

<li x-data="{ isOpen: {{ $isParameterSectionActive ? 'true' : 'false' }} }" class="mx-1 my-2 relative">
    <a href=""
    class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]"
    :class="{ 'bg-[#851E54]': isOpen }"
    @click.prevent="isOpen = !isOpen">
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="Report" class="h-5 w-5 mr-2">
        <span class="text-sm">Report</span>
        <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle"
             class="h-5 w-5 mr-2 absolute right-1 transition-transform duration-300"
             :class="{ 'rotate-180': isOpen }">
    </a>

    <ul x-show="isOpen"
        x-transition:enter="transition-all duration-300 ease-out"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-screen"
        x-transition:leave="transition-all duration-200 ease-in"
        x-transition:leave-start="opacity-100 max-h-screen"
        x-transition:leave-end="opacity-0 max-h-0"
        class="ml-6 space-y-1 overflow-hidden">

        <li class="my-2">
            <a href="{{ route('compliance-monitoring') }}"
            class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('compliance-monitoring') ? 'bg-[#DB0C16]' : '' }}">
            <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="compliance" class="h-5 w-5 mr-2">
                <span class="text-sm">Compliance Monitoring</span>
            </a>
        </li>

        <li class="my-2">
            <a href="{{ route('parameter-report') }}"
            class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('parameter-report') ? 'bg-[#DB0C16]' : '' }}">
            <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar.svg') }}" alt="parameter" class="h-5 w-5 mr-2">
                <span class="text-sm">Parameter Result</span>
            </a>
        </li>


    </ul>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('users') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('users') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ Vite::asset('resources/assets/icons/icon-user.svg') }}" alt="Settings" class="h-5 w-5 mr-2">
        <span class="text-sm">User Management</span>
    </a>
</li>
