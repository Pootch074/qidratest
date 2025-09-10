@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-50" x-data="{ showSectionModal: false, showClientTypeModal: false, selectedSection: null }">
    @php $authUser = Auth::user(); @endphp

    <div class="p-4 sm:ml-64">

        {{-- Scanned ID Table --}}
        <div class="bg-white rounded-lg p-4 overflow-x-auto shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Scanned ID</h2>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full bg-white border border-gray-200">
                    <thead class="bg-[#2e3192] text-white">
                        <tr>
                            <th class="px-4 py-2 border-b text-left">Full Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $index => $client)
                            <tr>
                                <td class="px-4 py-2 border-b">{{ $client->full_name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Generate Queue Button --}}
            <div class="mt-4 flex justify-end">
                <button @click="showSectionModal = true"
                    class="px-6 py-2 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 transition duration-200 shadow">
                    Generate Queue
                </button>
            </div>

            {{-- Hidden forms for each section --}}
            @foreach($sections as $section)
                <form id="form-{{ $section->id }}" 
                    action="{{ route('pacd.generate', $section->id) }}" 
                    method="POST" class="hidden">
                    @csrf
                    <input type="hidden" name="client_type" id="client_type_{{ $section->id }}">
                </form>
            @endforeach

            {{-- Section Selection Modal --}}
            <div x-show="showSectionModal"
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
                x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-140 p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Select Section</h3>

                    <div class="grid grid-cols-3 gap-3">
                        @foreach($sections as $section)
                            <button @click="selectedSection={{ $section->id }}; showSectionModal=false; showClientTypeModal=true;"
                                    class="px-4 py-2 bg-[#2e3192] hover:bg-[#5057c9] text-white rounded-lg shadow">
                                {{ strtoupper($section->section_name) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-4 text-right">
                        <button @click="showSectionModal = false" class="text-gray-500 hover:text-gray-700">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            {{-- Client Type Modal --}}
            <div x-show="showClientTypeModal" 
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
                x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-80 p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Choose Client Type</h3>
                    <div class="flex justify-around">
                        <button @click="
                                    document.getElementById('client_type_' + selectedSection).value='regular';
                                    document.getElementById('form-' + selectedSection).submit();
                                    showClientTypeModal=false;"
                                class="px-4 py-2 bg-[#2e3192] hover:bg-[#5057c9] text-white rounded-lg shadow">
                            Regular
                        </button>
                        <button @click="
                                    document.getElementById('client_type_' + selectedSection).value='priority';
                                    document.getElementById('form-' + selectedSection).submit();
                                    showClientTypeModal=false;"
                                class="px-4 py-2 bg-[#ee1c25] hover:bg-[#F4676E] text-white rounded-lg shadow">
                            Priority
                        </button>
                    </div>
                    <div class="mt-4 text-right">
                        <button @click="showClientTypeModal = false; selectedSection=null" 
                                class="text-gray-500 hover:text-gray-700">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

