@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200">
    @include('layouts.inc.pacdsidebar')

    <div class="p-4 sm:ml-64">

        <!-- Resume Transaction Modal -->
        <div id="resumeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Resume Transaction</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Are you sure you want to resume this transaction and generate a new queue number?
                </p>
                <div class="flex justify-end space-x-2">
                    <button onclick="closeResumeModal()"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</button>
                    <button id="generateQueueBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Generate Queue</button>
                </div>
            </div>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Returnees</h2>
            <div class="overflow-x-auto flex-1">
                <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                    <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">Queue Number</th>
                            {{-- <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Full Name</th> --}}
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                            <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Window</th>
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
                                            case 'priority': $prefix = 'P'; break;
                                            case 'regular': $prefix = 'R'; break;
                                            case 'deferred': $prefix = 'D'; break;
                                            default: $prefix = strtoupper(substr($queue->client_type, 0, 1));
                                        }
                                    @endphp
                                    {{ $prefix . str_pad($queue->queue_number, 3, '0', STR_PAD_LEFT) }}
                                </td>
                                {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $queue->full_name }}</td> --}}
                                <td class="px-6 py-4 whitespace-nowrap">{{ $queue->step->step_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $queue->window->window_number ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $queue->section->section_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="resumeTransaction({{ $queue->id }}, {{ $queue->step_id ?? 'null' }})"
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
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No Returnees found for yesterday.
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
let selectedTransactionId = null;
let selectedStepId = null;

function resumeTransaction(id, stepId) {
    selectedTransactionId = id;
    selectedStepId = stepId;
    document.getElementById("resumeModal").classList.remove("hidden");
}

function closeResumeModal() {
    document.getElementById("resumeModal").classList.add("hidden");
}

// 🔹 Reusable Print Ticket Function (from index.blade.php)
function printTicket(data) {
    const ticketHtml = `
        <!doctype html>
        <html>
        <head>
        <meta charset="utf-8">
        <title>Queue Ticket</title>
        <style>
        body { display:flex;align-items:center;justify-content:center;font-family:Arial,Helvetica,sans-serif;margin:0;background:#fff; }
        .ticket { width:210px;padding:10px;text-align:center; }
        .logo { font-size:16px;font-weight:700;margin-bottom:4px; }
        .section { font-size:14px;font-weight:600;margin:0 0 6px 0;color:#2e3192; }
        .number { font-size:42px;margin:12px 0;font-weight:900;letter-spacing:2px; }
        .meta { font-size:12px;margin:2px 0;color:#333; }
        .small { font-size:10px;color:#555;margin-top:8px;display:block; }
        @media print { @page { size:58mm auto; margin:3mm; } .ticket{width:100%;padding:0;} }
        </style>
        </head>
        <body>
        <div class="ticket">
            <div class="logo">🏛️ DSWD Service Center</div>
            <div class="section">${data.section}</div>
            <div class="number">${data.queue_number}</div>
            <div class="meta">${data.client_type} Client</div>
            <div class="meta">Step: ${data.step_number}</div>
            <small class="small">Issued: ${data.created_at}</small>
        </div>
        </body>
        </html>
    `;

    const printWindow = window.open('', '', 'width=400,height=600');
    printWindow.document.write(ticketHtml);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); }, 150);
}

// 🔹 Generate queue (with print)
document.getElementById("generateQueueBtn").addEventListener("click", () => {
    fetch(`/transactions/${selectedTransactionId}/resume`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            step_id: selectedStepId
        }),
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            printTicket(data); // ✅ Use reusable function
            closeResumeModal();
            location.reload();
        } else {
            alert(data.message || "Something went wrong");
        }
    })
    .catch(err => console.error(err));
});
</script>
@endsection

