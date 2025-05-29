@extends('layouts.main')
@section('title', 'RMT Management')

@section('content')

    <div class="container mx-auto p-4 bg-white rounded-xl">

        <table
            id="table"
            class="w-full border-collapse border border-gray-200"
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
                        <span>Members</span>
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
            @foreach ($assignments as $a)

                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->lgu->name ?? '' }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->user->first_name ?? '' }} {{ $a->user->last_name ?? '' }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->user->first_name ?? '' }} {{ $a->user->last_name ?? '' }}</td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">
                        <a href="{{ url('/periods/manage/' . $a->id) }}"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="View">
                            <span class="text-[#667085] text-xs">Edit</span>
                        </a>
                        <a href="{{ url('/periods/manage/' . $a->id) }}"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="View">
                            <span class="text-[#667085] text-xs">Assign</span>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('script')
{{--    @include('admin.periods.ascript')--}}
@endsection
