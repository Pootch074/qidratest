@extends('layouts.main')
@section('title', 'Questionnaires')

@section('content')


    <h2 class="mb-5">
        {{-- <span class="bg-[#2E3192] text-l inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">{{ $questionnaire->questionnaire_name }}</span> --}}
        
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
    </h2>

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