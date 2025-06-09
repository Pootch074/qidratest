@extends('layouts.main')
@section('title', 'Compliance Monitoring')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-between mb-4">
            <button class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
                2025 Monitoring Period
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
            </button>
            @include('admin.reports.create')
        </div>

        <div class="text-xl font-bold mb-4 text-[#667085]">
            <h1 class="text-center">COMPLIANCE MONITORING</h1>
        </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full table-fixed text-sm text-center text-gray-700">
    <thead>
        <tr class="bg-white border-b border-gray-200">
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">PARAMETER/FUNCTIONAL AREA</th>
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">WEIGHT PER INDICATOR</th>
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">PREVIOUS INDEX SCORE</th>
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">NEW INDEX SCORE</th>
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">STATUS</th>

        </tr>
    </thead>
</table>

<table class="w-full table-fixed text-sm text-left text-gray-700">
    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
        <tr>
            <th class="px-6 py-3 text-left" colspan="5">
                A. Administration and Organization
            </th>
        </tr>
    </thead>
    <tbody>
        <tr class="bg-white border-b border-gray-200">
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">
                1. Vision, Mission, Goals and Organizational Structure
            </th>

            <td class="border px-6 py-4 text-center">3.00%</td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
        </tr>
        <tr class="bg-white border-b border-gray-200">
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">
                2. Human Resource Management and Development
            </th>

            <td class="border px-6 py-4 text-center">11.00%</td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
        </tr>
        <tr class="bg-white border-b border-gray-200">
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">
                3. Public Financial Management
            </th>

            <td class="border px-6 py-4 text-center">9.00%</td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
        </tr>
        <tr class="bg-white border-b border-gray-200">
            <th class="border px-6 py-4 font-medium text-gray-900 break-words">
                4. Support Services
            </th>

            <td class="border px-6 py-4 text-center">8.00%</td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
            <td class="border px-6 py-4"></td>
        </tr>
    </tbody>
</table>

</div>


        
    </div>

@endsection

@section('script')
    @include('admin.periods.script')
@endsection