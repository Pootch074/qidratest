@extends('layouts.main')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]" x-data="{ showModal: false, selectedSection: null }">
    @php $authUser = Auth::user(); @endphp

    <div class="p-4">

        {{-- ✅ Navigation Bar --}}
        <nav class="bg-white rounded-lg p-4 mb-6 shadow flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0 md:space-x-4">
            <div class="flex items-center space-x-4">
                {{-- Transactions Button --}}
                <a href="{{ route('pacd.transactions.table') }}"
                class="px-4 py-2 rounded font-semibold text-white bg-[#150e60] hover:bg-blue-700 transition duration-200">
                    Transactions
                </a>

                {{-- Sections Button --}}
                <a href="{{ route('pacd.sections.cards') }}"
                class="px-4 py-2 rounded font-semibold text-white bg-[#150e60] hover:bg-blue-700 transition duration-200">
                    Sections
                </a>
            </div>
        </nav>
    </div>
</div>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
