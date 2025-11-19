@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
    @php $authUser = Auth::user(); @endphp
    <div class="w-full p-4 bg-gray-200" x-data="randomFunc({{ $authUser->section_id }})">
        @include('layouts.inc.pacdsidebar')
        <div class="p-4 sm:ml-64">
            {{-- Transactions Table --}}
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Returnees</h2>
                <div class="overflow-x-auto flex-1">
                    <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">
                                    Queue Number</th>
                                {{-- <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Full Name</th> --}}
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                                {{-- <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Window</th> --}}
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Section</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            @forelse ($pendingQueues as $queue)
                                <tr data-id="{{ $queue->id }}">
                                    <td class="px-6 py-4 font-semibold queue-number">
                                        {{ $queue->queue_label }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap step-number">
                                        {{ $queue->step->step_number ?? 'N/A' }}
                                    </td>
                                    {{-- <td class="px-6 py-4 whitespace-nowrap">{{ $queue->window->window_number ?? 'N/A' }}
                                    </td> --}}
                                    <td class="px-6 py-4 whitespace-nowrap section-name">
                                        {{ $queue->section->section_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button @click="resumeTransaction({{ $queue->id }})"
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
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500 font-medium">
                                        🚫 No Returnees found for yesterday.
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
        // Make it global so Alpine always finds it
        window.randomFunc = function(userSectionId) {
            return {

                // 🧩 STATE
                userSectionId: userSectionId,

                // 🖨️ Print ticket with given data
                generateQueue(data) {
                    const ticketHtml = this.buildTicketHtml(data);

                    const iframe = document.createElement('iframe');
                    iframe.style.position = 'fixed';
                    iframe.style.right = '9999px';
                    iframe.style.width = '0';
                    iframe.style.height = '0';
                    iframe.style.border = '0';

                    document.body.appendChild(iframe);

                    const doc = iframe.contentDocument || iframe.contentWindow.document;
                    doc.open();
                    doc.write(ticketHtml);
                    doc.close();

                    iframe.onload = () => {
                        const pw = iframe.contentWindow;

                        pw.onafterprint = () => {
                            try {
                                document.body.removeChild(iframe);
                            } catch {}
                        };

                        pw.focus();
                        setTimeout(() => pw.print(), 150);
                    };
                },

                // 🖨️ Build ticket HTML
                buildTicketHtml(data) {
                    return `
                <!doctype html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Queue Ticket</title>
                    <style>
                        @page { size: 2in 2.5in; margin: 0; }
                        body {
                            margin: 0; padding: 0;
                            width: 2in; height: 2in;
                            font-family: Arial, Helvetica, sans-serif;
                        }
                        .ticket {
                            width: 2in; height: 2.5in;
                            padding: 0.15in;
                            border-radius: 0.1in;
                            border: 0.02in dashed #333;
                            text-align: center;
                            box-sizing: border-box;
                            overflow: hidden;
                        }
                        .section { color: #2e3192; font-weight: 700; font-size: 14pt; margin: 0; }
                        .number { font-size: 36pt; margin: 0.1in 0; font-weight: 900; letter-spacing: 2px; }
                        .meta { font-size: 10pt; margin: 0.05in 0; }
                        .small { font-size: 8pt; font-weight: bold; margin-top: 0.1in; }
                    </style>
                </head>
                <body>
                    <div class="ticket">
                        <div class="section">${this.escapeHtml(data.section)}</div>
                        <div class="number">${this.escapeHtml(data.queue_number)}</div>
                        <div class="meta">${this.escapeHtml(data.client_type)} Client</div>
                        <div class="meta">Step ${this.escapeHtml(data.step_number)}</div>
                        <small class="small">${this.escapeHtml(new Date().toLocaleString())}</small>
                    </div>
                </body>
                </html>
            `.trim();
                },

                // 🛡️ HTML ESCAPER
                escapeHtml(str) {
                    return String(str || '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                },

                // 🔄 Resume transaction and create new queue
                resumeTransaction(id) {
                    fetch(`/transactions/${id}/resume`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {

                                // Build the ticket data from controller response
                                const data = {
                                    section: response.section,
                                    queue_number: response.queue_number,
                                    client_type: response.client_type,
                                    step_number: response.step_number,
                                };

                                // Determine number of copies based on section
                                const copies = (this.userSectionId == 15) ? 2 : 1;

                                // Print the ticket 'copies' times
                                for (let i = 0; i < copies; i++) {
                                    this.generateQueue(data);
                                }

                                // ✅ Remove the old transaction row from the table
                                const row = document.querySelector(`tr[data-id='${id}']`);
                                if (row) row.remove();
                            }
                        })
                        .catch(err => console.error("Error:", err));
                },


            };
        }
    </script>

@endsection
