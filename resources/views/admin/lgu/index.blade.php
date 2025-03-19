@extends('layouts.main')
@section('title', 'LGU Profiling')

@section('content')

    <div x-data="lguTable" x-init="fetchLgus()" class="container mx-auto p-4 bg-white rounded-xl">

        @include('admin.lgu.search')

        <table class="w-full border-collapse border border-gray-200" x-data="{ selectAll: false, selected: [] }">
            <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <input type="checkbox" x-model="selectAll"
                           @change="selected = selectAll ? lgus.map(lgu => lgu.id) : []"/>
                </th>
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
                        <span>Region</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Province</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Type</span>
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
            <template x-for="lgu in lgus" :key="lgu.id">
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-200 px-4 py-2 text-sm">
                        <input type="checkbox" :id="'lgu-' + lgu.id" :value="lgu.id" x-model="selected"/>
                    </td>
                    <td class="border border-gray-200 px-4 py-2 text-sm" x-text="lgu.name"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]" x-text="lgu.region"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]" x-text="lgu.province"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]" x-text="lgu.type"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">
                        <a href="#" @click.prevent="editLgu(lgu)"
                           class="border border-[#667085] hover:bg-blue-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4"
                                 alt="Edit LGU Profile">
                            <span class="text-[#667085] text-xs">Edit</span>
                        </a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>

@endsection

@section('script')
    @include('admin.lgu.script')
@endsection
