<div class="bg-white shadow rounded-lg p-6 max-w-md mx-auto mt-4">
    <h2 class="text-xl font-bold mb-4">Add New Client</h2>

    @if (session()->has('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Full Name:</label>
            <input type="text"
                   wire:model="full_name"
                   placeholder="Enter full name"
                   class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('full_name') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Submit
        </button>
    </form>
</div>
