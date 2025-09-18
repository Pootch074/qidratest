@extends('layouts.main')
@section('title', 'ASSESSMENT')
@section('header')
@endsection

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

                <button type="submit" id="saveButton" class="w-full bg-blue-600 text-white py-2 rounded">
                    Save
                </button>
            </form>

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
            <h2 class="text-xl font-semibold mb-4">Registered List</h2>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="py-2 px-4 border">Name</th>
                            <th class="py-2 px-4 border">Date Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr>
                            <td class="py-2 px-4 border">{{ $client->client_name }}</td>
                            <td class="py-2 px-4 border">{{ $client->created_at->format('F j, Y h:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const previewImage = document.getElementById("previewImage");
    const placeholder = document.getElementById("preview-placeholder");
    const saveBtn = document.getElementById('saveButton');
    const loadingModal = document.getElementById('loadingModal');
    const statusModal = document.getElementById('statusModal');
    const closeModalBtn = document.getElementById('closeModal');

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
</script>

@endsection
