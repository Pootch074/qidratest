@extends('layouts.pacd')
@section('title', 'PACD')
@section('header')
@endsection

@section('content')
@php $authUser = Auth::user(); @endphp
<div class="w-full p-4 bg-gray-200" 
     x-data="queueApp({{ $authUser->section_id }})">
    

    <div class="p-4 sm:ml-64">
        {{-- Scanned ID Table --}}
        <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Generate Ticket</h2>
            <div class="overflow-x-auto flex-1">

                {{-- Section Buttons (always visible) --}}
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

                {{-- Modal --}}
                <div x-show="showModal" 
                    class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50"
                    x-cloak>
                    <div class="bg-white rounded-lg shadow-lg w-80 p-6">
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Choose Client Type</h3>
                        <div class="flex flex-wrap justify-center gap-3">
                            {{-- Regular --}}
                            <button type="button"
                                @click="generateQueue(selectedSection, 'regular').then(() => reset())"
                                class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 
                                       hover:bg-gradient-to-br focus:ring-1 focus:outline-none 
                                       focus:ring-blue-300 dark:focus:ring-blue-800 shadow-lg shadow-blue-500/50 
                                       dark:shadow-lg dark:shadow-blue-800/80 font-medium rounded-lg 
                                       text-xl px-5 py-2.5 text-center me-2 mb-2">
                                Regular
                            </button>

                            {{-- Priority --}}
                            <button type="button"
                                @click="generateQueue(selectedSection, 'priority').then(() => reset())"
                                class="text-white bg-gradient-to-r from-red-400 via-red-500 to-red-600 
                                       hover:bg-gradient-to-br focus:ring-1 focus:outline-none 
                                       focus:ring-red-300 dark:focus:ring-red-800 shadow-lg shadow-red-500/50 
                                       dark:shadow-lg dark:shadow-red-800/80 font-medium rounded-lg 
                                       text-xl px-5 py-2.5 text-center me-2 mb-2">
                                Priority
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
</div>
@endsection

@section('scripts')
<script>
function queueApp(userSectionId) {
    return {
        showSections: true,
        showModal: false,
        selectedSection: null,
        clientName: '',
        userSectionId: userSectionId, // keep track of logged-in user’s section_id

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

                // Decide how many times to print
                const copies = (this.userSectionId == 15) ? 2 : 1;

                for (let i = 0; i < copies; i++) {
                    const iframe = document.createElement('iframe');
                    Object.assign(iframe.style, {
                        position: 'fixed', right: '9999px', width: '0', height: '0', border: '0'
                    });
                    document.body.appendChild(iframe);

                    const doc = iframe.contentDocument || iframe.contentWindow.document;
                    doc.open(); doc.write(ticketHtml); doc.close();

                    iframe.onload = () => {
                        const printWindow = iframe.contentWindow;

                        printWindow.onafterprint = () => {
                            try { document.body.removeChild(iframe); } catch {}
                        };

                        printWindow.focus();
                        setTimeout(() => { printWindow.print(); }, 150);
                    };
                }

                this.reset();

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
                            <div class="meta">${this.escapeHtml(data.client_name)}</div>
                            <small class="small">${this.escapeHtml(new Date().toLocaleString())}</small>
                        </div>
                    </body>
                </html>
            `.trim();
        },

        escapeHtml(str) {
            return String(str || '').replace(/&/g,'&amp;')
                                    .replace(/</g,'&lt;')
                                    .replace(/>/g,'&gt;');
        },

        reset() {
            this.showModal = false;
            this.selectedSection = null;
            this.clientName = '';
        }
    };
}
</script>
<script>
// 🔄 Check session validity every 10 seconds
setInterval(async () => {
    try {
        const res = await fetch("{{ route('session.check') }}", {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!res.ok) return; // network or server issue
        const data = await res.json();

        if (!data.active) {
            alert("You have been logged out because your account was accessed from another device.");
            window.location.href = "{{ route('login') }}";
        }
    } catch (err) {
        console.warn('Session check failed:', err);
    }
}, 5000); // every 10 seconds
</script>

@endsection

