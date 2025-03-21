@extends('layouts.main')
@section('title', 'Dashboard')

@section('content')

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Total Assessment</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">004</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ Vite::asset('resources/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Pending Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">004</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ Vite::asset('resources/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Completed Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">004</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ Vite::asset('resources/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Extension Request</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">014</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ Vite::asset('resources/assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
    </div>

    <div class="flex gap-4 mt-8">
        <div class="w-1/2">
            <h3 class="text-xl font-semibold text-gray-800">Assigned Assessments</h3>

            <div class="bg-white shadow-md rounded-lg p-4 mt-4">
                <div class="flex flex-col md:flex-row justify-between items-center mb-4">
                    <div class="relative w-full md:w-1/2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-search.png') }}" alt="Search" class="h-5 w-5">
                        </span>
                        <input type="text" placeholder="Search" class="pl-10 pr-4 py-2 w-full border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center space-x-2 mt-2 md:mt-0">
                        <img src="{{ Vite::asset('resources/assets/icons/icon-filter.png') }}" alt="Filter" class="h-5 w-5">
                        <span class="text-gray-700">Filters</span>
                    </div>
                </div>

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
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 10; $i++)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-200 px-4 py-2 text-sm">Davao City</td>
                            <td class="border border-gray-200 px-4 py-2 text-sm">Pending</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>


        <div class="w-1/2">

            <h3 class="text-xl font-semibold text-gray-800">Calendar</h3>

            <div class="mx-auto bg-white p-6 shadow-lg rounded-lg p-4 mt-4">

                <div class="flex justify-between items-center mb-4">
                    <button class="px-3 py-1 bg-gray-200 rounded">&#9665;</button>
                    <h2 class="text-lg font-semibold">March 2025</h2>
                    <button class="px-3 py-1 bg-gray-200 rounded">&#9655;</button>
                </div>
                <hr class="my-3" />
                <div class="grid grid-cols-7 text-center text-gray-700 font-semibold">
                    <div class="py-2">Sun</div>
                    <div class="py-2">Mon</div>
                    <div class="py-2">Tue</div>
                    <div class="py-2">Wed</div>
                    <div class="py-2">Thu</div>
                    <div class="py-2">Fri</div>
                    <div class="py-2">Sat</div>
                </div>
                <div class="grid grid-cols-7 text-center">
                    <div class="py-2 text-gray-300">26</div>
                    <div class="py-2 text-gray-300">27</div>
                    <div class="py-2 text-gray-300">28</div>
                    <div class="py-2 text-gray-300">29</div>
                    <div class="py-2 text-gray-300">30</div>
                    <div class="py-2">1</div>
                    <div class="py-2">2</div>
                    <div class="py-2">3</div>
                    <div class="py-2">4</div>
                    <div class="py-2">5</div>
                    <div class="py-2">6</div>
                    <div class="py-2">7</div>
                    <div class="py-2">8</div>
                    <div class="py-2">9</div>
                    <div class="py-2">10</div>
                    <div class="py-2">11</div>
                    <div class="py-2">12</div>
                    <div class="py-2">13</div>
                    <div class="py-2">14</div>
                    <div class="py-2">15</div>
                    <div class="py-2">16</div>
                    <div class="py-2">17</div>
                    <div class="py-2">18</div>
                    <div class="py-2">19</div>
                    <div class="py-2">20</div>
                    <div class="py-2">21</div>
                    <div class="py-2">22</div>
                    <div class="py-2">23</div>
                    <div class="py-2">24</div>
                    <div class="py-2">25</div>
                    <div class="py-2">26</div>
                    <div class="py-2">27</div>
                    <div class="py-2">28</div>
                    <div class="py-2">29</div>
                    <div class="py-2">30</div>
                    <div class="py-2">31</div>
                    <div class="py-2 text-gray-300">1</div>
                    <div class="py-2 text-gray-300">2</div>
                    <div class="py-2 text-gray-300">3</div>
                    <div class="py-2 text-gray-300">4</div>
                    <div class="py-2 text-gray-300">5</div>
                    <div class="py-2 text-gray-300">6</div>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
