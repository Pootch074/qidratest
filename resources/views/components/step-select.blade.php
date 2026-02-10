<div class="mb-4">
    <label for="{{ $name }}" class="block text-gray-700 text-2xl font-medium mb-1">Step Name</label>
    <select id="{{ $name }}" name="{{ $name }}" required class="mt-1 block w-full border rounded-md p-2">
        @foreach ($steps() as $step)
            <option value="{{ $step }}" @selected($step === $selected)>{{ $step }}</option>
        @endforeach
    </select>
    <p id="{{ $name }}Error" class="text-red-600 text-sm mt-1 hidden"></p>
</div>
