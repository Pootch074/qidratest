@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">

    <div class="p-4 sm:ml-64">

        {{-- Dashboard cards --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            @for ($i = 0; $i < 6; $i++)
                <div class="flex items-center justify-center h-24 rounded-sm bg-gray-50">
                    <p class="text-2xl text-gray-400">
                        <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                        </svg>
                    </p>
                </div>
            @endfor
        </div>

        {{-- Transactions table --}}
        <div class="bg-gray-50 rounded-lg p-4 overflow-x-auto shadow-md">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">Transactions</h2>

            @include('admin.transactions.table') {{-- Table partial --}}
        </div>

    </div>
</div>
@endsection
