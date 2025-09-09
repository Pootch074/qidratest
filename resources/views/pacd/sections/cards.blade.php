@extends('layouts.main')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]" x-data="{ showModal: false, selectedSection: null }">
    @php $authUser = Auth::user(); @endphp
    {{-- Back Navigation --}}
    <nav class="bg-white rounded-lg p-4 mb-6 shadow flex items-center space-x-4">
        <a href="{{ route('pacd') }}"
           class="px-4 py-2 rounded font-semibold text-white bg-red-800  hover:bg-[#d92d27] transition duration-200">
            Back
        </a>
    </nav>

    {{-- Dashboard / Section Buttons --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            @foreach($sections as $section)
                @if($authUser->user_type == 3 || $authUser->section_id == $section->id)
                    <form id="form-{{ $section->id }}" 
                          action="{{ route('pacd.generate', $section->id) }}" 
                          method="POST" 
                          class="section-form">
                        @csrf
                        <input type="hidden" name="client_type" id="client_type_{{ $section->id }}">
                        <button type="button"
                                @click="showModal = true; selectedSection = {{ $section->id }}"
                                class="w-full h-24 flex items-center justify-center rounded-lg bg-[#150e60] text-white font-bold shadow-md transition hover:bg-blue-700">
                            {{ strtoupper($section->section_name) }}
                        </button>
                    </form>
                @endif
            @endforeach
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
                        class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow">
                    Regular
                </button>
                <button @click="document.getElementById('client_type_' + selectedSection).value='priority'; 
                                document.getElementById('form-' + selectedSection).submit();" 
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow">
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
@endsection
