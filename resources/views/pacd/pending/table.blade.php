@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">
    @include('layouts.inc.pacdsidebar')

    <div class="p-4 sm:ml-64">

    {{-- Transactions Table --}}
    <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Pending Queues</h2>
        <div class="overflow-x-auto flex-1">
            <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">Queue Number</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Full Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                    @forelse ($pendingQueues as $queue)
                        <tr>
                            <td class="px-6 py-4 font-semibold">
                                @php
                                    switch (strtolower($queue->client_type)) {
                                        case 'priority':
                                            $prefix = 'P';
                                            break;
                                        case 'regular':
                                            $prefix = 'R';
                                            break;
                                        case 'returnee':
                                            $prefix = 'T'; // 👈 force returnee to use T
                                            break;
                                        default:
                                            $prefix = strtoupper(substr($transaction->client_type, 0, 1));
                                    }
                                @endphp
                                {{ $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap">{{ $queue->full_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $queue->step->step_number ?? 'N/A' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">{{ $queue->section->section_name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
<button onclick="resumeTransaction({{ $queue->id }})"
    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 
           hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-green-300 
           dark:focus:ring-green-800 shadow-lg shadow-green-500/50 
           dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg 
           text-sm px-5 py-2.5 text-center me-2 mb-2">
    <i class="fas fa-play"></i> Resume Transaction
</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No pending queues found for yesterday.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
        
    </div>

         </div>

    
</div>
@endsection


@section('scripts')
<script>
function resumeTransaction(id) {
    fetch(`/transactions/${id}/resume`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
        },
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert("Transaction resumed!");
            location.reload();
        }
    });
}
</script>
@endsection