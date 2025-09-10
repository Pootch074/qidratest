@extends('layouts.main')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-50" 
     x-data="{ showSections: false, showModal: false, selectedSection: null, clientName: '' }">
    @php $authUser = Auth::user(); @endphp

    @include('layouts.inc.pacdsidebar')

    <div class="p-4 sm:ml-64">
        <div class="bg-white rounded-lg p-4 overflow-x-auto shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manual Queue</h2>

            {{-- Step 1: Input Name + Generate Queue --}}
            <div class="flex items-center gap-4 mb-6">
                <input type="text" x-model="clientName" 
                       placeholder="Enter Client Name" 
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#2e3192] focus:outline-none">
                <button @click="if(clientName.trim() !== '') showSections = true"
                        class="px-6 py-2 rounded-lg font-semibold text-white bg-green-600 hover:bg-green-700 transition duration-200 shadow">
                    Generate Queue
                </button>
            </div>

            {{-- Step 2: Section Buttons (hidden until name entered + button clicked) --}}
            <div x-show="showSections" x-cloak>
                <div class="grid grid-cols-3 gap-4 mb-6">
                    @foreach($sections as $section)
                        @if($authUser->user_type == 3 || $authUser->section_id == $section->id)
                            <form id="form-{{ $section->id }}" 
                                action="{{ route('pacd.generate', $section->id) }}" 
                                method="POST" 
                                class="section-form">
                                @csrf
                                <input type="hidden" name="client_type" id="client_type_{{ $section->id }}">
                                <input type="hidden" name="manual_client_name" :value="clientName">
                                <button type="button"
                                        @click="showModal = true; selectedSection = {{ $section->id }}"
                                        class="w-full h-24 flex items-center justify-center rounded-lg bg-[#2e3192] text-white font-bold shadow-md transition hover:bg-[#5057c9]">
                                    {{ strtoupper($section->section_name) }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Client Type Modal --}}
            <div x-show="showModal" 
                class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
                x-cloak>
                <div class="bg-white rounded-lg shadow-lg w-80 p-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Choose Client Type</h3>
                    <div class="flex justify-around">
                        <button @click="document.getElementById('client_type_' + selectedSection).value='regular'; 
                                        document.getElementById('form-' + selectedSection).submit();" 
                                class="px-4 py-2 bg-[#2e3192] hover:bg-[#5057c9] text-white rounded-lg shadow">
                            Regular
                        </button>
                        <button @click="document.getElementById('client_type_' + selectedSection).value='priority'; 
                                        document.getElementById('form-' + selectedSection).submit();" 
                                class="px-4 py-2 bg-[#ee1c25] hover:bg-[#F4676E] text-white rounded-lg shadow">
                            Priority
                        </button>
                    </div>
                    <div class="mt-4 text-right">
                        <button @click="showModal = false" 
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
