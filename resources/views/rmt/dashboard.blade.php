@extends('layouts.main')
@section('title', 'Dashboard')

@section('header')
    <!-- fullcalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {

        var events = @json($events);

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          fixedWeekCount: false,
          events: events
        });
        calendar.render();
      });
    </script>
    <!-- fullcalendar end -->
@endsection

@section('content')

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Total Assessment</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $total }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('build/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Pending Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $pending }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('build/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Completed Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $completed }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('build/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Extension Request</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $extension }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('build/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
    </div>

    <div class="flex gap-4 mt-8">
        <div class="w-1/2">
            <h3 class="text-xl font-semibold text-gray-800">Assigned Assessments</h3>

            <div class="bg-white shadow-md rounded-lg p-4 mt-4">
                {{-- <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                    <div class="relative w-full md:w-1/2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <img src="{{ asset('build/assets/icons/icon-search.png') }}" alt="Search" class="h-5 w-5">
                        </span>
                        <input type="text" placeholder="Search" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center space-x-2 mt-2 md:mt-0">
                        <img src="{{ asset('build/assets/icons/icon-filter.png') }}" alt="Filter" class="h-5 w-5">
                        <span class="text-gray-700">Filters</span>
                    </div>
                </div> --}}

                <table class="w-full border-collapse border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-200 px-4 py-2 text-left">
                                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                                    <span>LGU Name</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>
                                </div>
                            </th>
                            <th class="border border-gray-200 px-4 py-2 text-left">
                                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                                    <span>Status</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>
                                </div>
                            </th>
                            <th class="border border-gray-200 px-4 py-2 text-left">
                                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                                    <span>Actions</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="size-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                                    </svg>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assessments as $assessment)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-4 py-2 text-sm">{{ $assessment->lgu->name }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-sm">{{ ucfirst($assessment->status) }}</td>
                            <td class="border border-gray-200 px-4 py-2 text-sm">
                                @if ($assessment->status == 'on-going')
                                    <a href="#" class="py-2 px-3 rounded-[15px] bg-[#FFCC00] text-xs w-fit">Request for Extension</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


        <div class="w-1/2">

            <h3 class="text-xl font-semibold text-gray-800">Calendar</h3>

            <div class="bg-white shadow-md rounded-lg p-4 mt-4">
                <div id="calendar"></div>
            </div>

        </div>
    </div>
</div>

@endsection

