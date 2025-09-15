@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200" 
     x-data="queueApp()">

    @php $authUser = Auth::user(); @endphp
    @include('layouts.inc.pacdsidebar')

    <div class="p-4 sm:ml-64">
        <div class="bg-white rounded-lg p-4 overflow-x-auto shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Manual Queue</h2>

            {{-- Step 1: Input Name + Generate Queue --}}
            <div class="flex items-center gap-4 mb-6">
                <input type="text" x-model="clientName" 
                       placeholder="Enter Client Name" 
                       class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:border-[#2e3192] focus:ring-1 focus:ring-[#2e3192] transition outline-none">
                <button 
                    :disabled="clientName.trim() === ''"
                    @click="showSections = true"
                    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br 
                           focus:ring-1 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 
                           shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 
                           font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2
                           disabled:opacity-50 disabled:cursor-not-allowed">
                    Generate Queue
                </button>
            </div>

            {{-- Step 2: Section Buttons --}}
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
                                <input type="hidden" name="manual_client_name" x-bind:value="clientName">
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

            {{-- Modal for Client Type --}}
<div x-show="showModal" 
    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
    x-cloak>
    <div class="bg-white rounded-lg shadow-lg w-80 p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Choose Client Type</h3>
        <div class="flex flex-wrap justify-center gap-3">
            {{-- Regular --}}
            <button type="button"
                @click="generateQueue(selectedSection, 'regular').then(() => reset())"
                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Regular
            </button>

            {{-- Priority only for section_id == 15 --}}
            @if($authUser->section_id == 15)
                <button type="button"
                    @click="generateQueue(selectedSection, 'priority').then(() => reset())"
                    class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 hover:bg-gradient-to-br focus:ring-1 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                    Priority
                </button>
            @endif

            {{-- Returnee --}}
            <button type="button"
                @click="generateQueue(selectedSection, 'returnee').then(() => reset())"
                class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 shadow-lg shadow-lime-500/50 dark:shadow-lg dark:shadow-lime-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Returnee
            </button>
        </div>
        <div class="mt-4 text-right">
            <button @click="reset()" 
                    class="text-gray-500 hover:text-gray-700">
                Cancel
            </button>
        </div>
    </div>
</div>


            {{-- Success Message --}}
            @if(session('success'))
                <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function queueApp() {
    return {
        showSections: false,
        showModal: false,
        selectedSection: null,
        clientName: '',

        async generateQueue(sectionId, type) {
            try {
                const url = window.routes.pacdGenerate.replace('__SECTION__', sectionId);
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        client_type: type,
                        manual_client_name: this.clientName
                    })
                });

                const data = await res.json();
                if (!data || !data.success) {
                    console.error('Server returned error:', data);
                    alert('Error generating queue.');
                    return;
                }

                const ticketHtml = this.buildTicketHtml(data);

                // Create hidden iframe for printing
                const iframe = document.createElement('iframe');
                Object.assign(iframe.style, {
                    position: 'fixed', right: '9999px', width: '0', height: '0', border: '0'
                });
                document.body.appendChild(iframe);

                const doc = iframe.contentDocument || iframe.contentWindow.document;
                doc.open(); doc.write(ticketHtml); doc.close();

                iframe.onload = () => {
                    const printWindow = iframe.contentWindow;

                    // Reset after print OR cancel
                    printWindow.onafterprint = () => {
                        try {
                            const root = document.querySelector('[x-data]');
                            if (root && root.__x && root.__x.$data && typeof root.__x.$data.reset === 'function') {
                                root.__x.$data.reset();
                            }
                        } catch (e) {
                            console.warn("Reset after print failed", e);
                        }

                        try { document.body.removeChild(iframe); } catch {}
                    };

                    printWindow.focus();
                    setTimeout(() => { printWindow.print(); }, 150);
                };

            } catch (err) {
                console.error('Request/print error:', err);
                alert('Printing failed — check console for details.');
            }
        },

        buildTicketHtml(data) {
            return `
                <!doctype html>
                <html>
                <head>
                <meta charset="utf-8">
                <title>Queue Ticket</title>
                <style>
                body{display:flex;align-items:center;justify-content:center;font-family:Arial,Helvetica,sans-serif;margin:0;}
                .ticket{width:320px;padding:26px;border-radius:12px;border:2px dashed #333;text-align:center;}
                .section{color:#2e3192;font-weight:700;font-size:18px;margin:0}
                .number{font-size:72px;margin:18px 0;font-weight:900;letter-spacing:2px}
                .meta{font-size:16px;margin:6px 0;color:#333}
                .small{font-size:12px;color:#666;margin-top:12px}
                @media print{ @page { margin:6mm; } .ticket{border:none;width:100%;padding:6mm;} }
                </style>
                </head>
                <body>
                <div class="ticket">
                    <div class="section">${this.escapeHtml(data.section)}</div>
                    <div class="number">${this.escapeHtml(data.queue_number)}</div>
                    <div class="meta">${this.escapeHtml(data.client_type)} Client</div>
                    <div class="meta">${this.escapeHtml(data.client_name)}</div>
                    <small class="small">Generated: ${this.escapeHtml(new Date().toLocaleString())}</small>
                </div>
                </body>
                </html>`.trim();
        },

        escapeHtml(str) {
            return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        },

        reset() {
            this.showSections = false;
            this.showModal = false;
            this.clientName = '';
        }
    };
}
</script>
@endsection
