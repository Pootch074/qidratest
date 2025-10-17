@extends('layouts.main')
@section('title', 'ASSESSMENT')
@section('header')
@endsection

<style>
  .table-container {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
  }
  .table-scroll {
    max-height: 520px; /* change as needed */
    overflow-y: auto;
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  thead th {
    position: sticky;
    top: 0;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 10px;
    text-align: left;
    font-size: 16px;
    text-transform: uppercase;
  }
  tbody td {
    padding: 8px;
    border-bottom: 1px solid #f3f4f6;
  }
  tbody tr:hover { background: #fbfcfd; }
</style>

@section('content')
<div class="w-full p-4 bg-[#cbdce8] min-h-screen">
    <h1 class="text-2xl font-bold mb-6">ID SCAN</h1>

    <div class="flex flex-col w-full gap-4">
        {{-- LEFT SIDE - CAMERA FORM --}}
        <div class="w-full bg-white shadow rounded-2xl p-4 items-center">
            <form id="imageForm" action="{{ route('upload.image') }}" method="POST" enctype="multipart/form-data" class="w-full">
                @csrf

                <!-- Capture using device camera app -->
                <label for="id_image" class="block mb-2 font-semibold">Take a Picture</label>
                <input 
                    type="file" 
                    id="id_image" 
                    name="id_image" 
                    accept="image/*" 
                    capture="environment" 
                    onchange="showPreview(this.files[0])"
                    class="border rounded p-2 w-full mb-4"
                >

                <!-- Preview area -->
                <div id="preview-container" 
                    style="width: 66.6%; aspect-ratio: 4/3; border: 2px dashed #aaa; border-radius: 12px; display: flex; align-items: center; justify-content: center; background: #f9f9f9; color: #777; font-size: 14px; overflow: hidden; margin: 20px auto; max-width: 800px;">
                    <span id="preview-placeholder">No image uploaded</span>
                    <img id="previewImage" src="" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                </div>

                <button type="submit" id="saveButton" 
                    class="w-full text-white py-2 rounded" 
                    style="background-color: #2e3192;">
                    SUBMIT
                </button>
            </form>

            <div id="textModal" 
                class="hidden fixed inset-0 z-50 flex items-center justify-center">
                
                <!-- Background Overlay -->
                <div class="absolute inset-0 bg-black/50 z-0" onclick="closeModal()"></div>

                    <div class="relative z-10 bg-white rounded-2xl shadow-lg p-6 w-80 text-center">
                        <h2 class="text-xl font-semibold mb-4">Select Name(s)</h2>

                        @php
                            $client = session('name');
                        @endphp

                        @if($client)
                            @php
                                $names = $client['name'] ?? [];
                            @endphp

                            <form method="POST" action="{{ route('save.name') }}" x-data="{ selected: [] }">
                                @csrf
                                <label class="block mb-2 font-semibold">Please confirm name(s):</label>
                                <input type="hidden" name="image" value="{{ $client['image'] ?? '' }}">

                                {{-- If multiple names, show as checkboxes --}}
                                @if(is_array($names) && count($names) > 1)
                                    @foreach($names as $name)
                                        <div class="flex items-center mb-2">
                                            <input 
                                                type="checkbox"
                                                id="name_{{ $loop->index }}" 
                                                value="{{ $name }}"
                                                x-model="selected"
                                                class="mr-2"
                                            >
                                            <label for="name_{{ $loop->index }}">{{ $name }}</label>
                                        </div>
                                    @endforeach

                                    {{-- One input field showing concatenated selected names --}}
                                    <input 
                                        type="text"
                                        name="confirmed_name"
                                        class="border rounded px-3 py-2 w-full mt-3"
                                        placeholder="Selected names will appear here..."
                                        x-model="selected.join(' ')"
                                    >

                                {{-- If only one name, show in a text input --}}
                                @elseif(is_array($names) && count($names) === 1)
                                    <input 
                                        type="text" 
                                        name="confirmed_name" 
                                        value="{{ $names[0] }}" 
                                        class="border rounded px-3 py-2 w-full"
                                    >
                                @else
                                    <p class="text-gray-600">No names available.</p>
                                @endif

                                <button 
                                    type="submit" 
                                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                                >
                                    Confirm Selection
                                </button>
                            </form>
                        @endif
                    </div>
            </div>

            <!-- Loading Modal -->
            <div id="loadingModal" 
                class="hidden fixed inset-0 z-50 flex items-center justify-center">
                
                <!-- Background Overlay -->
                <div class="absolute inset-0 bg-black/50 z-0"></div>

                <!-- Modal Box (z-10 makes it sit above overlay) -->
                <div class="relative z-10 bg-white rounded-2xl shadow-lg p-6 w-80 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-10 h-10 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <h2 class="text-lg font-semibold">Processing...</h2>
                    <p class="text-gray-600">Please wait while we save your image.</p>
                </div>
            </div>

            <!-- Result Modal -->
            <div id="statusModal" 
                class="hidden fixed inset-0 z-50 flex items-center justify-center">
                
                <!-- Background Overlay -->
                <div class="absolute inset-0 bg-black/50 z-0"></div>

                <!-- Modal Box -->
                <div class="relative z-10 bg-white rounded-2xl shadow-lg p-6 w-96 text-center">
                    <h2 class="text-lg font-semibold mb-4">
                        {{ session('success') ? '✅ Success!' : (session('error') ? '❌ Error!' : '') }}
                    </h2>
                    <p>
                        {{ session('success') ?? session('error') }}
                    </p>
                    <div class="mt-6">
                        <button id="closeModal" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            OK
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hidden canvas for processing image --}}
            <canvas id="snapshot" class="hidden"></canvas>
        </div>

        {{-- RIGHT SIDE - TABLE --}}
        <div class="w-full bg-white shadow rounded-2xl p-4 items-center">
            <h2 class="text-xl font-semibold mb-4">Registered Clients</h2>
            <div class="overflow-x-auto table-scroll">
                <table class="w-full border-gray-300 rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-4 px-4">Name</th>
                            <th class="py-4 px-4">Date Registered</th>
                            <th class="py-4 px-4">Captured ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td class="py-4 px-4">{{ $client->client_name }}</td>
                            <td class="py-4 px-4">{{ $client->created_at->format('F j, Y h:i A') }}</td>
                            <td class="py-4 px-4">
                                <div x-data="{ openModal: false, modalImage: '' }">
                                    <button 
                                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700"
                                        @click="openModal = true; modalImage = '{{ asset('storage/uploads/' . $client->image) }}'">
                                        View ID
                                    </button>

                                    <div 
                                        x-show="openModal" 
                                        class="fixed inset-0 z-50 flex items-center justify-center"
                                        x-cloak
                                    >
                                        <!-- Background Overlay -->
                                        <div class="absolute inset-0 bg-black/50 z-0"></div>

                                        <div class="bg-white rounded-lg shadow-lg p-2 relative max-w-lg w-full">
                                            <button 
                                                class="absolute top-2 right-2 text-red-600 hover:text-black text-3xl font-bold leading-none"
                                                @click="openModal = false"
                                            >
                                                &times;
                                            </button>

                                            <img :src="modalImage" alt="Client Image" class="rounded-lg max-h-[80vh] mx-auto">
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- <td class="px-4 py-2 border text-center">
                                {{ asset('storage/' . $client->image) }}
                            </td> -->
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $clients->appends(request()->input())->links() }}
                </div>
                <form method="GET" action="{{ route('idscan') }}" class="mb-4">
                    <label for="per_page">Show: </label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                    entries
                </form>
        </div>
    </div>
</div>

<script>
    const previewImage = document.getElementById("previewImage");
    const placeholder = document.getElementById("preview-placeholder");
    const saveBtn = document.getElementById('saveButton');
    const loadingModal = document.getElementById('loadingModal');
    const textModal = document.getElementById('textModal');
    const statusModal = document.getElementById('statusModal');
    const closeModalBtn = document.getElementById('closeModal');
    const image = document.getElementById("id_image");

    // Example when setting preview
    function showPreview(file) {
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                previewImage.style.display = "block";
                placeholder.style.display = "none";
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.src = "";
            previewImage.style.display = "none";
            placeholder.style.display = "block";
        }
    }

    // Show loading modal on form submit
    document.getElementById('imageForm').addEventListener('submit', function() {
        loadingModal.classList.remove('hidden');
    });

    @if(session('name'))
        window.addEventListener('load', () => {
            textModal.classList.remove('hidden');
        });
    @endif

    // Show status modal if session message exists
    @if(session('success') || session('error'))
        window.addEventListener('load', () => {
            loadingModal.classList.add('hidden'); // hide loading just in case
            statusModal.classList.remove('hidden');
        });
    @endif

    // Close status modal
    closeModalBtn?.addEventListener('click', () => {
        statusModal.classList.add('hidden');
    });

    image.addEventListener("input", function() {
        if (this.value.trim() !== "") {
            saveBtn.disabled = false;  // enable button
        } else {
            saveBtn.disabled = true;   // disable button
        }
    });

    function closeModal() {
        loadingModal.classList.add('hidden');
        textModal.classList.add('hidden');
        statusModal.classList.add('hidden');
    }
</script>

@endsection
