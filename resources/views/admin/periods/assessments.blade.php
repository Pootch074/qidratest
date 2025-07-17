@extends('layouts.main')
@section('title', 'Assessments')

@section('content')

    <div class="container mx-auto p-4 bg-white rounded-xl">

{{--        @include('admin.periods.search')--}}

        <table
            id="table"
            class="w-full border-collapse border border-gray-200"
            x-data="{ assessments: window.assessments, selectAll: false, selected: [] }"
        >
            <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>LGU Name</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Team Leader</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Assessment Start Date</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Assessment End Date</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Status</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Actions</span>
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            @php
                $filtered = $assessments->filter(fn($a) => $a->user_id);
            @endphp
            @forelse ($filtered as $a)

                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->lgu->name ?? '' }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->user->first_name ?? '' }} {{ $a->user->last_name ?? '' }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]">
                        {{ $a->assessment_start_date ? \Carbon\Carbon::parse($a->assessment_start_date)->format('M d, Y') : "" }}
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]">
                        {{$a->assessment_end_date ? \Carbon\Carbon::parse($a->assessment_end_date)->format('M d, Y') : "" }}
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]">
            <span
                class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                    {{
                        $a->status === 'ongoing' ? 'bg-green-100 text-green-800' :
                        'bg-gray-100 text-yellow-800'
                    }}">
                <span class="{{ $a->status === 'ongoing' ? 'text-green-500' : 'text-gray-500' }}">⬤</span>
                {{ ucfirst($a->status) }}
            </span>
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">
                        <a href="{{ url('/periods/manage/' . $a->id) }}"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ asset('build/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="View">
                            <span class="text-[#667085] text-xs">View</span>
                        </a>
                        <a href="{{ url('/periods/manage/' . $a->id) }}"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ asset('build/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="View">
                            <span class="text-[#667085] text-xs">Approve</span>
                        </a>
                    </td>
                </tr>
            @empty
                <tr class="hover:bg-gray-50">
                    <td colspan="6" class="border border-gray-200 px-4 py-2 text-sm">
                        No LGU assigned to RMT yet.
                        <a class="text-blue-900 font-medium" href="{{ route('rmt-assignments') }}">Go to RMT Management</a>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

@endsection

@section('script')
{{--    @include('admin.periods.ascript')--}}
@endsection
