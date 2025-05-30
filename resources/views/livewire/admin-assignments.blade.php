<div>
    <div class="mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search LGU or RMT..."
            class="w-1/3 border border-gray-300 rounded px-3 py-2 text-sm"
        />
{{--        <p>You typed: {{ $search }}</p>--}}
    </div>

    <table class="w-full border-collapse border border-gray-200">
        <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-200 px-4 py-2 text-left cursor-pointer" wire:click="sortBy('lgus.name')">
                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                    <span>LGU Name</span>
                    @if ($sortField === 'lgus.name')
                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="{{ $sortDirection === 'asc' ? 'M12 3v18m0 0-7.5-7.5M12 21l7.5-7.5' : 'M12 21V3m0 0-7.5 7.5M12 3l7.5 7.5' }}" />
                        </svg>
                    @endif
                </div>
            </th>
            <th class="border border-gray-200 px-4 py-2 text-left cursor-pointer" wire:click="sortBy('users.first_name')">
                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                    <span>Team Leader</span>
                    @if ($sortField === 'users.first_name')
                        <svg class="size-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="{{ $sortDirection === 'asc' ? 'M12 3v18m0 0-7.5-7.5M12 21l7.5-7.5' : 'M12 21V3m0 0-7.5 7.5M12 3l7.5 7.5' }}" />
                        </svg>
                    @endif
                </div>
            </th>
            <th class="border border-gray-200 px-4 py-2 text-left">
                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                    <span>Members</span>
                </div>
            </th>
            <th class="border border-gray-200 px-4 py-2 text-left">
                <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                    <span>Actions</span>
                </div>
            </th>
        </tr>
        </thead>
        <tbody>
        @forelse ($assignments as $a)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->lgu_name }}</td>
                <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->rmt_first_name }} {{ $a->rmt_last_name }}</td>
                <td class="border border-gray-200 px-4 py-2 text-sm">{{ $a->rmt_first_name }} {{ $a->rmt_last_name }}</td>
                <td class="border border-gray-200 px-4 py-2 text-sm space-x-2">
                    <a href="{{ url('/periods/manage/' . $a->id) }}"
                       class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                        <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="Edit">
                        <span class="text-[#667085] text-xs">Edit</span>
                    </a>
                    <a href="{{ url('/periods/manage/' . $a->id) }}"
                       class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                        <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="Assign">
                        <span class="text-[#667085] text-xs">Assign</span>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-gray-500 text-sm py-4">No assignments found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $assignments->links() }}
    </div>
</div>
