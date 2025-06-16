<div x-data="assignmentModal()">
    <!-- Search Bar -->
    <div class="mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search LGU or RMT..."
            class="w-1/3 border border-gray-300 rounded px-3 py-2 text-sm"
        />
    </div>

    <!-- Table -->
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
                    <span>Status</span>
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
                <td class="border border-gray-200 px-4 py-2 text-sm">{!! $this->getAssesstors($a->id) !!}</td>
                <td class="border border-gray-200 px-4 py-2 text-sm text-[#667085]"
                    x-data="{
                        status: '{{ $a->status }}',
                        toggleStatus() { $wire.toggleStatus({{ $a->id }}) }
                    }">
                    <a href="#" @click.prevent="toggleStatus">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium cursor-pointer"
                              :class="{
                                  'bg-green-100 text-green-800': status === 'completed',
                                  'bg-yellow-100 text-yellow-800': status === 'on-going',
                                  'bg-gray-200 text-gray-800': status === 'pending',
                                  'bg-red-200 text-red-800': status === 'request_for_extension',
                              }">
                            <span x-text="status.charAt(0).toUpperCase() + status.slice(1).replaceAll('_', ' ')"></span>
                        </span>
                    </a>
                </td>
                <td class="border border-gray-200 px-4 py-2 text-sm space-x-2">
                    <a href="#"
                       @click.prevent="openModal({ id: '{{ $a->id }}', lgu_name: '{{ $a->lgu_name }}' })"
                       class="border border-[#667085] hover:bg-red-200 inline-flex items-center gap-1 px-3 py-1 rounded-full">
                        <img src="{{ Vite::asset('resources/assets/icons/icon-edit.svg') }}" class="h-4 w-4" alt="Assign">
                        <span class="text-[#667085] text-xs">Assign</span>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-gray-500 text-sm py-4">No assignments found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $assignments->links() }}
    </div>

    <!-- MODAL -->
    <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 mt-[-4rem]">
            <h2 class="text-xl font-semibold mb-4">Assign Team</h2>
            <form @submit.prevent="assign">
                <input type="hidden" name="id" :value="currentData.id">

                <div class="mb-4">
                    <p>Assigning for: <strong x-text="currentData.lgu_name"></strong></p>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Team Leader</label>
                    <select x-model="selectedTeamLeader" class="w-full border rounded px-3 py-2">
                        <option value="">-- Select Team Leader --</option>
                        @foreach($teamLeaders as $tl)
                            <option value="{{ $tl->id }}">{{ $tl->first_name }} {{ $tl->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Members</label>
                    <div class="border rounded px-3 py-2 max-h-60 overflow-y-auto">
                        @foreach($rmts as $rmt)
                            <label class="flex items-center mb-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    :value="{{ $rmt->id }}"
                                    x-model="selectedRmts"
                                    class="mr-2 rounded"
                                >
                                <span>{{ $rmt->first_name }} {{ $rmt->last_name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit" class="bg-[#2E3192] text-white px-4 py-2 rounded">Assign</button>
                    <a href="#" @click="closeModal" class="bg-gray-300 text-black px-4 py-2 rounded">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- AlpineJS Controller -->
<script>
    function assignmentModal() {
        return {
            showModal: false,
            selectedTeamLeader: '',
            selectedRmts: [],
            currentData: { id: null, lgu_name: '' },

            openModal(data) {
                this.currentData = data;
                this.showModal = true;
            },

            assign() {
                fetch("{{ route('api-periods-assign') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id: this.currentData.id,
                        team_leader: this.selectedTeamLeader,
                        members: this.selectedRmts
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Success:", data);
                    // this.closeModal();
                    location.reload();
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            },

            closeModal() {
                this.showModal = false;
            }
        }
    }
</script>
