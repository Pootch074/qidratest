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
function generateQueue(sectionId, type) {
    const clientNameInput = document.querySelector('input[x-model="clientName"]');
    const clientName = clientNameInput ? clientNameInput.value : '';

    // Open window immediately (prevents popup blocking)
    const printWindow = window.open('', '', 'width=500,height=700');

    fetch(`/pacd/generate/${sectionId}`, {
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
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Fill content into the already-opened window
            printWindow.document.write(`
                <div style="text-align:center;font-family:sans-serif;padding:20px;">
                    <h2 style="margin-bottom:5px;">${data.section}</h2>
                    <h1 style="font-size:60px;margin:10px 0;">${data.queue_number}</h1>
                    <p style="font-size:18px;margin:0;">${data.client_type} Client</p>
                    <p style="margin-top:10px;">${data.client_name}</p>
                </div>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        } else {
            alert('Error generating queue');
            printWindow.close();
        }
    })
    .catch(err => {
        console.error(err);
        printWindow.close();
    });
}


</script>
@endsection
