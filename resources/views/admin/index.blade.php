@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">

    <div class="p-4 sm:ml-64">
        {{-- Dashboard cards --}}
        <div class="grid grid-cols-4 gap-4 mb-6">

            {{-- Waiting Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Waiting Clients</p>
                <p class="text-2xl font-bold text-blue-600 waitingCount">{{ $waitingCount }}</p>
            </div>

            {{-- Pending Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Pending Clients</p>
                <p class="text-2xl font-bold text-yellow-600 pendingCount">{{ $pendingCount }}</p>
            </div>

            {{-- Serving Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Serving Clients</p>
                <p class="text-2xl font-bold text-green-600 servingCount">{{ $servingCount }}</p>
            </div>

            {{-- Priority Clients (visible only if section_id == 15) --}}
            @auth
                @if (Auth::user()->section_id == 15)
                    <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                        <p class="text-lg font-semibold text-gray-600">Priority Clients</p>
                        <p class="text-2xl font-bold text-red-600 priorityCount">{{ $priorityCount }}</p>
                    </div>
                @endif
            @endauth

            {{-- Regular Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Regular Clients</p>
                <p class="text-2xl font-bold text-gray-800 regularCount">{{ $regularCount }}</p>
            </div>

            {{-- Returnee Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Returnee Clients</p>
                <p class="text-2xl font-bold text-gray-800 returneeCount">{{ $returneeCount }}</p>
            </div>

            {{-- Completed Clients --}}
            <div class="flex flex-col items-center justify-center h-24 rounded-lg bg-white shadow">
                <p class="text-lg font-semibold text-gray-600">Completed Clients</p>
                <p class="text-2xl font-bold text-gray-800 completedCount">{{ $completedCount }}</p>
            </div>
        </div>
        <div id="transactionsTableContainer">
            @include('admin.transactions.table')
        </div>

    </div>
</div>
@endsection

@section('scripts')
{{-- <script>
document.addEventListener('DOMContentLoaded', () => {


    // Function to fetch latest transactions
    const fetchTransactions = () => {
        fetch("{{ route('admin.transactions.realtime') }}")
            .then(res => res.json())
            .then(data => {
                // Update counts
                document.querySelector('.waitingCount').textContent = data.counts.waitingCount;
                document.querySelector('.pendingCount').textContent = data.counts.pendingCount;
                document.querySelector('.servingCount').textContent = data.counts.servingCount;
                @if(Auth::user()->section_id == 15)
                    document.querySelector('.priorityCount').textContent = data.counts.priorityCount;
                @endif
                document.querySelector('.regularCount').textContent = data.counts.regularCount;
                document.querySelector('.completedCount').textContent = data.counts.completedCount;

                // Update transactions table
                document.querySelector('#transactionsTableContainer').innerHTML = data.table;
            })
            .catch(err => console.error(err));
    };

    // Fetch immediately
    fetchTransactions();

    // Fetch every 5 seconds (5000 ms)
    setInterval(fetchTransactions, 2000);
});
</script> --}}
@endsection


