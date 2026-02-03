@extends('layouts.pacd')
@section('title', 'Clients')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        @include('layouts.inc.pacdsidebar')

        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Client logs</h2>
                <div class="overflow-x-auto flex-1">
                    <div class="flex gap-4 mb-4">
                        <input type="text" id="searchName" placeholder="Search Name" class="px-3 py-2 border rounded w-1/3">
                        <input type="text" id="searchSection" placeholder="Search Section"
                            class="px-3 py-2 border rounded w-1/3">
                        <input type="time" id="searchTime" class="px-3 py-2 border rounded w-1/3">
                        <button id="clearFilters"
                            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">
                            Clear Filters
                        </button>
                    </div>

                    <table id="clientLogsTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">
                                    Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">
                                    Section</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">
                                    Phone Number
                                </th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">
                                    Entry Time
                                </th>

                            </tr>
                        </thead>
                        <tbody id="clientLogsBody" class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse ($clientlogs as $client)
                                <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                                    <td class="px-6 py-4 font-semibold fullname">
                                        {{ $client->fullname }}
                                    </td>
                                    <td class="px-6 py-4 section">
                                        {{ $client->section->section_name ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $client->phone_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 created_at">
                                        {{ $client->created_at->format('M d, Y g:i A') }}
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 font-medium">
                                        🚫 No client logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchName = document.getElementById('searchName');
            const searchSection = document.getElementById('searchSection');
            const searchTime = document.getElementById('searchTime');
            const clearBtn = document.getElementById('clearFilters');
            const tableBody = document.getElementById('clientLogsBody');

            const rows = tableBody.querySelectorAll('tr');

            function filterTable() {
                const nameValue = searchName.value.toLowerCase().trim();
                const sectionValue = searchSection.value.toLowerCase().trim();
                const timeValue = searchTime.value; // HH:mm

                rows.forEach(row => {
                    const fullname = row.querySelector('.fullname')?.textContent.toLowerCase() || '';
                    const section = row.querySelector('.section')?.textContent.toLowerCase() || '';
                    const createdAt = row.querySelector('.created_at')?.textContent || '';
                    const rowTime = createdAt.split(' ')[1] ?? '';

                    let isVisible = true;

                    if (nameValue && !fullname.includes(nameValue)) isVisible = false;
                    if (sectionValue && !section.includes(sectionValue)) isVisible = false;
                    if (timeValue && !rowTime.startsWith(timeValue)) isVisible = false;

                    row.style.display = isVisible ? '' : 'none';
                });
            }

            function clearFilters() {
                searchName.value = '';
                searchSection.value = '';
                searchTime.value = '';
                // Show all rows
                rows.forEach(row => row.style.display = '');
            }

            searchName.addEventListener('input', filterTable);
            searchSection.addEventListener('input', filterTable);
            searchTime.addEventListener('input', filterTable);
            clearBtn.addEventListener('click', clearFilters);
        });
    </script>

@endsection
