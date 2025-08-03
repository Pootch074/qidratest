@extends('layouts.main')
@section('title', 'Questionnaires')

@section('content')

    <div class="flex items-center gap-4 mb-4 bg-[#EEEFF1] p-5 rounded-full m-3 border border-[#E5E5EA]">
        @if(auth()->user()->user_type > 1)
        <!-- Dropdown -->
        <div wire:ignore x-data="lguDropdownComponent(@js($lgus))" x-init="init()" class="relative inline-block text-left w-[250px]">
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
        </div>
        @endif

        <div
            x-data="rootsComponent(@js($roots), {{ $currentRoot->id }})"
            x-init="init()"
            class="flex flex-wrap items-center gap-2"
        >
            <template x-for="root in roots" :key="root.id">
                <button
                    @click="select(root)"
                    class="transition-colors duration-200 rounded-full px-4 py-2 flex items-center gap-2 focus:outline-none cursor-pointer"
                    :class="currentRootId === root.id
                        ? 'bg-[#2E3192] text-white'
                        : 'text-[#B0B2B7]'"
                    x-text="root.name"
                ></button>
            </template>
        </div>
    </div>

    <div class="flex mb-4">
        <div class="w-4/6 bg-white p-[30px] rounded-lg m-3 shadow-md">
            @include('questionnaires.inc.questions')
        </div>
        <div class="w-2/6 bg-white p-[30px] rounded-lg m-3 shadow-md">
            @include('questionnaires.inc.nav')
        </div>
    </div>

@endsection

@section('script')
    @include('questionnaires.script')
@endsection

@push('scripts')
<script>
    const apiUrlTemplate = @js(route('api-deadlines-get', ['userId' => '__ID__']));
    const assessmentManagementUrl = @js(route('assessment-management'));

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

                // 🔁 Redirect to the assessment-management route with query param
                window.location.href = `${assessmentManagementUrl}?lgu_id=${option.id}`;
            },

            fetchDeadlines() {
                const url = apiUrlTemplate.replace('__ID__', {{ auth()->user()->id }});
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        this.deadlines = data || [];
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        this.deadlines = [];
                    });
            }
        };
    };

    window.rootsComponent = function(roots, initialRootId) {
        return {
            roots: roots,
            currentRootId: initialRootId,
            init() {
                const savedId = localStorage.getItem('selected_root_id');
                const match = this.roots.find(r => r.id == savedId);

                if (match) {
                    this.currentRootId = match.id;
                }
            },
            select(option) {
                this.currentRootId = option.id;
                localStorage.setItem('selected_root_id', option.id);
                window.location.href = `${assessmentManagementUrl}?root_id=${option.id}`;
            }
        };
    };
</script>
@endpush
