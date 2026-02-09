@extends('layouts.pacd')
@section('title', 'PACD')
@section('header')
@endsection

@section('content')
    @php $authUser = Auth::user(); @endphp
    <div class="w-full p-4 bg-gray-200" x-data="queueApp({{ $authUser->section_id }})">
        <div class="p-4 sm:ml-64">
            {{-- Scanned ID Table --}}
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Generate Ticket</h2>

                <div class="overflow-x-auto flex-1">
                    <div x-show="showSections" x-cloak>
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            @foreach ($sections as $section)
                                @if ($authUser->user_type == 3 || $authUser->section_id == $section->id)
                                    <form id="form-{{ $section->id }}" action="{{ route('pacd.generate', $section->id) }}"
                                        method="POST" class="section-form">
                                        @csrf
                                        <input type="hidden" name="client_type" id="client_type_{{ $section->id }}">
                                        <input type="hidden" name="manual_client_name" x-bind:value="clientName">
                                        <input type="hidden" name="manual_client_phone" x-bind:value="clientPhone">
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

                    <div x-show="showModal"
                        class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-sm bg-black/30 p-4"
                        x-cloak>
                        <div class="relative w-full max-w-3xl max-h-full">
                            <!-- Modal Container -->
                            <div
                                class="relative bg-gray-200 rounded-2xl shadow-2xl border-2 border-blue-900 transform transition-all duration-200 scale-95 opacity-100">

                                <!-- Close Button -->
                                <button type="button" @click="reset()"
                                    class="absolute top-3 right-3 text-gray-700 hover:text-gray-400 bg-transparent rounded-full w-8 h-8 flex items-center justify-center transition">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 14 14" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M1 1l6 6m0 0 6-6M7 7l6 6M7 7l-6-6" />
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>

                                <!-- Modal Header -->
                                <div class="px-6 pt-6 pb-4 text-center">
                                    <h3 class="text-3xl font-bold text-gray-700 mb-2">Provide client
                                        information</h3>
                                </div>

                                <!-- Modal Form -->
                                <form class="px-6 pb-6 space-y-6" method="POST" id="clientLog" x-data="{ clientType: '', clientName: '', clientPhone: '' }">
                                    @csrf
                                    <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-4 items-end">
                                        <div class="flex flex-col">
                                            <label for="client_name" class="text-gray-700 text-2xl font-medium mb-1">Client
                                                Full
                                                Name</label>
                                            <input type="text" name="client_name" id="client_name" required
                                                placeholder="Ex. Juan Dela Cruz"
                                                class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none transition"
                                                x-model="clientName">
                                        </div>
                                        <div class="flex flex-col">
                                            <label for="phone_number" class="text-gray-700 text-2xl font-medium mb-1">Phone
                                                Number</label>
                                            <input type="text" name="phone_number" placeholder="Optional" maxlength="11"
                                                id="phone_number"
                                                class="w-full h-14 px-4 rounded-xl border border-gray-300 bg-gray-50 focus:border-blue-700 focus:ring-1 focus:ring-blue-700 outline-none transition"
                                                x-model="clientPhone">
                                        </div>
                                    </div>

                                    <!-- Client Type Radio Buttons -->
                                    <div class="mt-6 flex flex-col md:flex-row justify-center gap-4 items-center">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="client_type" value="regular" x-model="clientType"
                                                :disabled="!clientName" class="form-radio h-8 w-8 text-blue-600">
                                            <span class="text-gray-700 text-2xl font-medium">Regular</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="client_type" value="priority" x-model="clientType"
                                                :disabled="!clientName" class="form-radio h-8 w-8 text-red-500">
                                            <span class="text-gray-700 text-2xl font-medium">Priority</span>
                                        </label>
                                    </div>

                                    <!-- Print Button -->
                                    <div class="mt-6 flex justify-center">
                                        <button type="button"
                                            @click="generateQueue(selectedSection, clientType).then(() => reset())"
                                            :disabled="!clientName || !clientType"
                                            class="px-6 py-3 bg-green-500 hover:bg-green-600 text-gray-700 font-semibold rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Print
                                        </button>
                                    </div>

                                    <!-- Cancel Button -->
                                    <div class="mt-4 flex justify-end text-center">
                                        <button type="button" @click="reset()"
                                            class="text-gray-700 hover:text-gray-400 transition">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    {{-- Success Message --}}
                    @if (session('success'))
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
                clientPhone: '',
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
                                manual_client_name: this.clientName,
                                manual_client_phone: this.clientPhone
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
                                position: 'fixed',
                                right: '9999px',
                                width: '0',
                                height: '0',
                                border: '0'
                            });
                            document.body.appendChild(iframe);

                            const doc = iframe.contentDocument || iframe.contentWindow.document;
                            doc.open();
                            doc.write(ticketHtml);
                            doc.close();

                            iframe.onload = () => {
                                const printWindow = iframe.contentWindow;

                                printWindow.onafterprint = () => {
                                    try {
                                        document.body.removeChild(iframe);
                                    } catch {}
                                };

                                printWindow.focus();
                                setTimeout(() => {
                                    printWindow.print();
                                }, 150);
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
                            <small class="small">${this.escapeHtml(new Date().toLocaleString())}</small>
                        </div>
                    </body>
                </html>
            `.trim();
                },

                escapeHtml(str) {
                    return String(str || '').replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;');
                },

                reset() {
                    this.showModal = false;
                    this.selectedSection = null;
                    this.clientName = '';
                    this.clientPhone = '';
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
                    // alert("You have been logged out because your account was accessed from another device.");
                    window.location.href = "{{ route('login') }}";
                }
            } catch (err) {
                console.warn('Session check failed:', err);
            }
        }, 5000); // every 10 seconds
    </script>

@endsection
