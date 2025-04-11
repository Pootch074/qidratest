@extends('layouts.main')
@section('title', 'Questionnaires')

@section('content')

    <div x-data="qTable" x-init="fetchQ()" class="container mx-auto p-4 bg-white rounded-xl">

        @include('admin.questionnaires.search')

        <table id="table" class="w-full border-collapse border border-gray-200" x-data="{ selectAll: false, selected: [] }">
            <thead>
            <tr class="bg-gray-100">
{{--                <th class="border border-gray-200 px-4 py-2 text-left">--}}
{{--                    <input type="checkbox" x-model="selectAll"--}}
{{--                           @change="selected = selectAll ? questionnaires.map(q => q.id) : []"/>--}}
{{--                </th>--}}
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Name</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Effectivity Date</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                             stroke="currentColor" class="size-3">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3"/>
                        </svg>
                    </div>
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
            <template x-for="q in questionnaires" :key="q.id">
                <tr class="hover:bg-gray-50">
{{--                    <td class="border border-gray-200 px-4 py-2 text-sm">--}}
{{--                        <input type="checkbox" :id="'q-' + q.id" :value="q.id" x-model="selected"/>--}}
{{--                    </td>--}}
                    <td class="border border-gray-200 px-4 py-2 text-sm" x-text="q.questionnaire_name"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]" x-text="q.effectivity_date"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]" x-text="q.status"></td>
                    <td class="border border-gray-200 px-4 py-2 text-sm">
                        <a href="#" @click.prevent="editQ(q)"
                           class="border border-[#667085] hover:bg-blue-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4"
                                 alt="Edit Questionnaire">
                            <span class="text-[#667085] text-xs">Edit</span>
                        </a>
                        <a href="#" @click.prevent="deleteQ(q.id)"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4"
                                 alt="Delete Questionnaire">
                            <span class="text-[#667085] text-xs">Delete</span>
                        </a>
                        <a :href="'/questionnaires/manage/' + q.id"
                           class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4"
                                 alt="View">
                            <span class="text-[#667085] text-xs">View</span>
                        </a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <div class="mt-4 flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span x-text="(currentPage - 1) * perPage + 1"></span> to <span x-text="Math.min(currentPage * perPage, questionnaires.length)"></span> of <span x-text="questionnaires.length"></span> results
                </p>
            </div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button @click="goToPage(currentPage - 1)" :disabled="currentPage === 1" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
                <template x-for="page in totalPages" :key="page">
                    <button @click="goToPage(page)" :class="{ 'bg-indigo-600 text-white': currentPage === page, 'bg-white text-gray-500 hover:bg-gray-50': currentPage !== page }" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium">
                        <span x-text="page"></span>
                    </button>
                </template>
                <button @click="goToPage(currentPage + 1)" :disabled="currentPage === totalPages" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>
        </div>
    </div>

@endsection

@section('script')
    @include('admin.questionnaires.script')
@endsection
