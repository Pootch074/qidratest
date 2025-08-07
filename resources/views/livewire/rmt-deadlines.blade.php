<div class="border border-gray-200 my-1">

    {{-- <div wire:ignore x-data="lguDropdownComponent(@js($lgus))" x-init="init()" class="relative inline-block text-left p-4 w-[250px]">
        <!-- Button -->
        <button @click="open = !open" type="button"
            class="bg-[#2E3192] text-white rounded-full px-4 py-2 flex items-center gap-2 focus:outline-none w-full justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 2a6 6 0 00-6 6c0 4.418 6 10 6 10s6-5.582 6-10a6 6 0 00-6-6zM8 8a2 2 0 114 0 2 2 0 01-4 0z"
                        clip-rule="evenodd" />
                </svg>
                <span x-text="selectedName"></span>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4 text-white transform transition-transform duration-200"
                :class="{ 'rotate-180': open }"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.27a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Dropdown List -->
        <ul x-show="open" @click.outside="open = false" x-transition
            class="absolute mt-2 w-full rounded-md shadow-lg bg-white text-black z-10 max-h-60 overflow-y-auto">
            <template x-for="option in options" :key="option.id">
                <li @click="select(option)"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    x-text="option.name">
                </li>
            </template>
        </ul>
    </div> --}}

    <!-- table -->
    <div x-data="lguDropdownComponent(@js($lgus))" x-init="init()" >
        <table class="w-full border-collapse border border-gray-200">
            <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-200 px-4 py-2 text-left cursor-pointer" wire:click="sortBy('lgus.name')">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>LGU Name</span>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left cursor-pointer" wire:click="sortBy('users.first_name')">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>Start Date</span>
                    </div>
                </th>
                <th class="border border-gray-200 px-4 py-2 text-left">
                    <div class="flex items-center space-x-1 text-xs text-[#667085] font-normal">
                        <span>End Date</span>
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
            <tbody x-show="deadlines.length > 0">
            <template x-for="(deadline, index) in deadlines" :key="deadline.id">
                    <tr>
                        <td class="border border-gray-200 px-4 py-2 text-left" x-text="deadline.lgu_name"></td>
                        <td class="border border-gray-200 px-4 py-2 text-left" x-text="deadline.assessment_start_date ? new Date(deadline.assessment_start_date).toLocaleDateString('en-US', { month: 'long', day: '2-digit', year: 'numeric' }) : ''"></td>
                        <td class="border border-gray-200 px-4 py-2 text-left" x-text="deadline.assessment_end_date ? new Date(deadline.assessment_end_date).toLocaleDateString('en-US', { month: 'long', day: '2-digit', year: 'numeric' }) : ''"></td>
                        <td class="border border-gray-200 px-4 py-2 text-left">
                            <span
                                class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-medium"
                                :class="'status-' + deadline.status"
                            >
                                <span
                                    class="h-2 w-2 rounded-full"
                                    :class="'status.' + deadline.status + '-dot'"
                                ></span>
                                <span class="font-normal capitalize" x-text="deadline.status"></span>
                            </span>
                        </td>
                        <td class="border border-gray-200 px-4 py-2 text-left">
                            <template x-if="deadline.status === 'closed'">
                                <a href="#"
                                   class="inline-flex items-center gap-1 rounded-full border border-gray-400 px-3 py-1 text-xs text-gray-600 hover:bg-gray-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Request for Extension
                                </a>
                            </template>
                        </td>
                    </tr>
                </template>
            </tbody>

            <!-- Fallback when empty -->
            <tbody x-show="deadlines.length === 0">
                <tr>
                    <td colspan="5" class="text-center text-gray-500 text-sm py-4">
                        No LGU is assigned yet. Please contact admin.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@push('scripts')
<script>
    const apiUrlTemplate = @js(route('api-deadlines-get', ['userId' => '__ID__']));

    window.lguDropdownComponent = function(lgus) {
        return {
            open: false,
            options: lgus,
            selectedId: null,
            selectedName: 'Select LGU',
            deadlines: [],
            initialized: false,

            init() {
                if (this.initialized) return;
                this.initialized = true;

                const savedId = localStorage.getItem('selected_lgu_id');
                const match = this.options.find(opt => opt.id == savedId);

                if (match) {
                    this.selectedId = match.id;
                    this.selectedName = match.name;
                } else if (this.options.length > 0) {
                    this.selectedId = this.options[0].id;
                    this.selectedName = this.options[0].name;
                    localStorage.setItem('selected_lgu_id', this.selectedId);
                }

                this.fetchDeadlines();
            },

            select(option) {
                this.selectedId = option.id;
                this.selectedName = option.name;
                this.open = false;
                localStorage.setItem('selected_lgu_id', option.id);
                this.fetchDeadlines();
            },

            fetchDeadlines() {
                const url = apiUrlTemplate.replace('__ID__', {{ auth()->user()->id }});
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        //console.log('Fetched deadlines:', data);
                        this.deadlines = data || [];
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        this.deadlines = [];
                    });
            }
        };
    }
</script>
@endpush
