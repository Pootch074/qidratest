<li class="mx-1 my-2">
    <a href="{{ route('dashboard') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('dashboard') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ asset('build/assets/icons/icon-dashboard.svg') }}" alt="Dashboard" class="h-5 w-5 mr-2">
        <span class="text-sm">Dashboard</span>
    </a>
</li>

@if (count(\App\Helpers\PeriodHelper::getLgus(auth()->user()->id)) > 0)
<li class="mx-1 my-2">
    <a href="{{ route('assessment-management') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('assessment-management') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ asset('build/assets/icons/icon-assessment.svg') }}" alt="Assessment" class="h-5 w-5 mr-2">
        <span class="text-sm">Assessment Management</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="{{ route('deadlines') }}" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16] {{ Request::is('deadlines') ? 'bg-[#DB0C16]' : '' }}">
        <img src="{{ asset('build/assets/icons/icon-deadline.svg') }}" alt="Deadlines" class="h-5 w-5 mr-2">
        <span class="text-sm">Deadlines</span>
    </a>
</li>

<li class="mx-1 my-2">
    <a href="#" class="transition-colors duration-200 flex items-center p-3 rounded-lg text-white hover:bg-[#DB0C16]">
        <img src="{{ asset('build/assets/icons/icon-parameters.svg') }}" alt="Parameter Result" class="h-5 w-5 mr-2">
        <span class="text-sm">Parameter Result</span>
    </a>
</li>

@endif
