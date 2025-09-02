@extends('layouts.main')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-[#cbdce8]">

    {{-- Back Navigation --}}
    <nav class="bg-white rounded-lg p-4 mb-6 shadow flex items-center space-x-4">
        <a href="{{ route('pacd') }}"
           class="px-4 py-2 rounded font-semibold text-white bg-red-800  hover:bg-[#d92d27] transition duration-200">
            Back
        </a>
    </nav>

    {{-- Transactions Table --}}
    <div class="bg-white rounded-lg p-4 overflow-x-auto shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Transactions</h2>
        <table class="min-w-full divide-y divide-gray-200 text-gray-700">
            <thead class="bg-[#150e60] text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">Queue Number</th>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Client Type</th>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Section</th>
                    <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($transactions as $transaction)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50 transition duration-200">
                        <td class="px-6 py-4 font-semibold">
                            {{ strtoupper(substr($transaction->client_type, 0, 1)) . str_pad($transaction->queue_number, 3, '0', STR_PAD_LEFT) }}
                        </td>

                        {{-- Client Type Badge --}}
                        <td class="px-6 py-4">
                            @if(strtolower($transaction->client_type) === 'priority')
                                <span class="px-2 py-1 rounded-full text-white text-xs bg-[#d92d27]">
                                    Priority
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-white text-xs bg-[#150e60]">
                                    Regular
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">{{ $transaction->step->step_number ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $transaction->section->section_name ?? '—' }}</td>

                        {{-- Status Badge --}}
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'waiting' => 'bg-yellow-400 text-yellow-900',
                                    'pending' => 'bg-orange-400 text-orange-900',
                                    'serving' => 'bg-green-500 text-white',
                                ];
                                $statusClass = $statusColors[strtolower($transaction->queue_status)] ?? 'bg-gray-300 text-gray-700';
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($transaction->queue_status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 font-medium">
                            🚫 No transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
