@extends('layouts.pacd')
@section('title', 'Transactions')
@section('header')
@endsection

@section('content')
<div class="w-full p-4 bg-gray-200" 
     x-data="{ showSections: false, showModal: false, selectedSection: null, clientName: '' }">

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
                            @click="generateQueue(selectedSection, 'regular')"
                            class="px-4 py-2 bg-[#2e3192] hover:bg-[#5057c9] text-white rounded-lg shadow">
                            Regular
                        </button>

                        {{-- Priority only for section_id == 15 --}}
                        @if($authUser->section_id == 15)
                            <button type="button"
                                @click="generateQueue(selectedSection, 'priority')"
                                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">
                                Priority
                            </button>
                        @endif

                        {{-- Returnee --}}
                        <button type="button"
                            @click="generateQueue(selectedSection, 'returnee')"
                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow">
                            Returnee
                        </button>
                    </div>
                    <div class="mt-4 text-right">
                        <button @click="showModal = false" 
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
window.generateQueue = async function(sectionId, type) {
    const clientNameInput = document.querySelector('input[x-model="clientName"]');
    const clientName = clientNameInput ? clientNameInput.value : '';

    try {
        const res = await fetch(`/pacd/generate/${sectionId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                client_type: type,
                manual_client_name: clientName
            })
        });

        // try to parse JSON, if server returns HTML this will throw
        const data = await res.json();

        if (!data || !data.success) {
            console.error('Server returned error:', data);
            alert('Error generating queue. Check console for details.');
            return;
        }

        // build the ticket HTML (centered visually)
        const ticketHtml = `
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Queue Ticket</title>
<meta name="viewport" content="width=device-width,initial-scale=1" />
<style>
  html,body{height:100%;margin:0;padding:0;-webkit-print-color-adjust:exact;}
  body{display:flex;align-items:center;justify-content:center;font-family:Arial,Helvetica,sans-serif;background:#fff;}
  .ticket{width:320px;padding:26px;border-radius:12px;border:2px dashed #333;text-align:center;box-sizing:border-box;}
  .section{color:#2e3192;font-weight:700;font-size:18px;margin:0}
  .number{font-size:72px;margin:18px 0;font-weight:900;letter-spacing:2px}
  .meta{font-size:16px;margin:6px 0;color:#333}
  .small{font-size:12px;color:#666;margin-top:12px}
  @media print{
    @page { margin: 6mm; }
    body { background: #fff; }
    .ticket { border: none; box-shadow: none; width: 100%; padding: 6mm; }
  }
</style>
</head>
<body>
  <div class="ticket">
    <div class="section">${escapeHtml(data.section)}</div>
    <div class="number">${escapeHtml(data.queue_number)}</div>
    <div class="meta">${escapeHtml(data.client_type)} Client</div>
    <div class="meta">${escapeHtml(data.client_name)}</div>
    <small class="small">Generated: ${escapeHtml(new Date().toLocaleString())}</small>
  </div>
</body>
</html>`.trim();

        // create hidden iframe
        const iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.right = '9999px'; // keep off-screen
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        document.body.appendChild(iframe);

        // write ticket into iframe
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        doc.open();
        doc.write(ticketHtml);
        doc.close();

        // wait a little for content to render then print
        iframe.onload = () => {
            try {
                iframe.contentWindow.focus();
                // small timeout to ensure layout/fonts are ready
                setTimeout(() => {
                    iframe.contentWindow.print();
                    // remove iframe after short delay
                    setTimeout(() => {
                        try { document.body.removeChild(iframe); } catch(e) {}
                    }, 700);
                }, 200);
            } catch (err) {
                console.warn('iframe print failed, falling back to popup', err);
                // fallback to popup if iframe fails
                fallbackPopupPrint(ticketHtml);
            }
        };

        // Some browsers fire onload inconsistently for doc.write content — ensure print anyway
        setTimeout(() => {
            try {
                if (iframe && iframe.contentWindow) {
                    iframe.contentWindow.focus();
                    iframe.contentWindow.print();
                    setTimeout(() => { try { document.body.removeChild(iframe); } catch(e) {} }, 700);
                }
            } catch (err) {
                console.warn('timeout print failed, fallback', err);
                fallbackPopupPrint(ticketHtml);
            }
        }, 600);

        // best-effort: close the Alpine modal
        try {
            const root = document.querySelector('[x-data]');
            if (root && root.__x && root.__x.$data) {
                root.__x.$data.showModal = false;
                root.__x.$data.showSections = false;
            } else if (window.Alpine) {
                // Alpine v3: try to find component
                const comp = window.Alpine.discover && window.Alpine.discover((el) => el.hasAttribute('x-data'));
                if (comp && comp.$data) comp.$data.showModal = false;
            } else {
                // fallback hide selector-based
                const modal = document.querySelector('[x-show="showModal"]');
                if (modal) modal.style.display = 'none';
            }
        } catch (e) { /* non-fatal */ }

    } catch (err) {
        console.error('Request/print error:', err);
        alert('Printing failed — check console for details.');
    }

    // small helper functions
    function escapeHtml(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function fallbackPopupPrint(html) {
        // last-resort: open popup (may be blocked)
        const w = 420, h = 560;
        const left = Math.max(0, Math.floor((screen.width - w) / 2));
        const top = Math.max(0, Math.floor((screen.height - h) / 2));
        const popup = window.open('', '', `width=${w},height=${h},left=${left},top=${top},noopener,noreferrer`);
        if (!popup) {
            alert('Popup blocked. Please allow popups for printing.');
            return;
        }
        popup.document.open();
        popup.document.write(html);
        popup.document.close();
        popup.focus();
        setTimeout(() => { try { popup.print(); popup.close(); } catch(e) { console.error(e); } }, 300);
    }
};
</script>

@endsection

