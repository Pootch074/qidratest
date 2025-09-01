@extends('layouts.main')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]" x-data="{ showModal: false, selectedSection: null }">

    <div class="p-4">
        {{-- Dashboard cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            @foreach($sections as $section)
                <form id="form-{{ $section->id }}" 
                      action="{{ route('pacd.generate', $section->id) }}" 
                      method="POST" 
                      class="section-form">
                    @csrf
                    <input type="hidden" name="client_type" id="client_type_{{ $section->id }}">
                    <button type="button"
                        @click="showModal = true; selectedSection = {{ $section->id }}"
                        class="w-full h-24 flex items-center justify-center rounded-lg bg-blue-500 hover:bg-blue-600 text-white font-bold shadow-md transition">
                        {{ strtoupper($section->section_name) }}
                    </button>
                </form>
            @endforeach
        </div>

        @if(session('success'))
            <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Transactions</h2>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Queue Number</th>
                        <th scope="col" class="px-6 py-3">Client Type</th>
                        <th scope="col" class="px-6 py-3">Step</th>
                        <th scope="col" class="px-6 py-3">Section</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            {{-- Queue Number --}}
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                {{ strtoupper(substr($transaction->client_type, 0, 1)) . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT) }}
                            </td>

                            {{-- Client Type --}}
                            <td class="px-6 py-4">
                                {{ ucfirst($transaction->client_type) }}
                            </td>

                            {{-- Step --}}
                            <td class="px-6 py-4">
                                {{ $transaction->step_id ?? '—' }}
                            </td>

                            {{-- Section --}}
                            <td class="px-6 py-4">
                                {{ $transaction->section->section_name ?? '—' }}
                            </td>

                            {{-- Status --}}
                            <td class="px-6 py-4">
                                {{ ucfirst($transaction->queue_status) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal --}}
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

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
