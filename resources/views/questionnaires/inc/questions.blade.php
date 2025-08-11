<h2 class="text-[#94969B] font-bold text-xl mb-5">{{ $parent->name }}</h2>
<div id="assessment-questionnaires" class="bg-[#EEEFF1] p-[30px]">

    <h2>
        <span class="bg-[#2E3192] text-lg inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">
            {{ $child->reference_number }}
        </span>
        <span class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">{{ $child->name }}</span>
    </h2>

    <hr class="my-5 border-[#CDCFD2] border-1">

    {{-- Means of Verification --}}
    <div class="bg-white p-3 rounded-lg my-5">
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Means of Verification</h3>

        <div class="options-container mt-5">
            @foreach ($means as $mean)
                @if (auth()->user()->user_type == 1)
                    <div class="option">
                @else
                    <div
                        x-data="movToggle({
                            route: '{{ route('api-assessment-mov') }}',
                            user_id: '{{ auth()->user()->id }}',
                            period_id: '{{ $periodId }}',
                            questionnaire_id: '{{ $questionnaireId }}',
                            lgu_id: '{{ $lguId }}',
                            mov_id: {{ $mean->id }},
                            initialChecked: {{ json_encode(in_array($mean->id, $checkedMeans ?? [])) }}
                        })"
                        @click="toggle()"
                        :class="['option', checked ? 'selected' : '']"
                    >
                @endif
                        <input
                            type="checkbox"
                            x-model="checked"
                            class="custom-checkbox"
                            :value="{{ $mean->id }}"
                            data-questionnaire="{{ $questionnaireId }}"
                        />
                        <span>{!! $mean->means !!}</span>
                    </div>
            @endforeach

            {{-- Optional: Others --}}
            @if (auth()->user()->user_type == 1)
                <div class="option">
            @else
                <div
                    x-data="movToggle({
                        route: '{{ route('api-assessment-mov') }}',
                        user_id: '{{ auth()->user()->id }}',
                        period_id: '{{ $periodId }}',
                        questionnaire_id: '{{ $questionnaireId }}',
                        lgu_id: '{{ $lguId }}',
                        mov_id: 0,
                        initialChecked: {{ json_encode(in_array(0, $checkedMeans ?? [])) }}
                    })"
                    @click="toggle()"
                    :class="['option', checked ? 'selected' : '']"
                >
            @endif
                    <input
                        type="checkbox"
                        x-model="checked"
                        class="custom-checkbox"
                        :value="0"
                        data-questionnaire="{{ $questionnaireId }}"
                    />
                    <span>Others</span>
                </div>
        </div>
    </div>

    {{-- Service Performance Level --}}
    <div class="bg-white p-3 rounded-lg my-5">
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Service Performance Level</h3>

        <div class="options-container mt-5">
            @foreach ($levels as $level)
                @if (auth()->user()->user_type == 1)
                    <div class="option">
                @else
                    <div
                        x-data="levelToggle({
                            route: '{{ route('api-assessment-level') }}',
                            user_id: '{{ auth()->user()->id }}',
                            period_id: '{{ $periodId }}',
                            questionnaire_id: '{{ $questionnaireId }}',
                            lgu_id: '{{ $lguId }}',
                            level_id: {{ $level->id }},
                            initialChecked: {{ json_encode($selectedLevelId == $level->id) }}
                        })"
                        @click="toggle()"
                        :class="['option', checked ? 'selected' : '']"
                        data-questionnaire="{{ $questionnaireId }}"
                    >
                @endif
                        <input
                            type="radio"
                            x-model="checked"
                            class="custom-checkbox"
                            :value="{{ $level->id }}"
                            name="level_option"
                        />
                        <div class="option-text">
                            <p><b>{{ $level->level > 0 ? "Level $level->level" : "Low" }}</b></p>
                            <small>{!! $level->remarks !!}</small>
                        </div>
                    </div>
            @endforeach

            {{-- Optional: Not Applicable --}}
            @if (auth()->user()->user_type == 1)
            <div class="option">
                @else
                    <div
                        x-data="levelToggle({
                            route: '{{ route('api-assessment-level') }}',
                            user_id: '{{ auth()->user()->id }}',
                            period_id: '{{ $periodId }}',
                            questionnaire_id: '{{ $questionnaireId }}',
                            lgu_id: '{{ $lguId }}',
                            level_id: 1,
                            initialChecked: {{ json_encode($selectedLevelId == 1) }}
                        })"
                        @click="toggle()"
                        :class="['option', checked ? 'selected' : '']"
                    >
                @endif
                        <input
                            type="radio"
                            x-model="checked"
                            class="custom-checkbox"
                            value="1"
                            name="level_option"
                        />
                        <div class="option-text">
                            <p><b>Not Applicable</b></p>
                            <small>Indicator not applicable to LGU</small>
                        </div>
                    </div>
            </div>
    </div>

    {{-- Remarks --}}
    <div
        class="bg-white p-3 rounded-lg my-5"
        x-data='remarksEditor({
            route: "{{ route('api-assessment-remarks') }}",
            initialContent: @json($existingRemarks ?? ''),
            period_id: {{ $periodId }},
            lgu_id: {{ $lguId }},
            questionnaire_id: {{ $questionnaireId }},
            user_id: {{ auth()->user()->id }},
            init() { this.$refs.remarks.innerHTML = this.initialContent }
        })'
    >
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Remarks</h3>
        <small class="block ml-2 mb-5 text-[#677489]">Observations and Suggestions</small>

        <div
            id="remarks"
            x-ref="remarks"
            class="wysiwyg bg-white mt-5 h-60 overflow-auto border rounded-lg p-3 focus:outline-none"
            contenteditable="true"
            role="textbox"
            aria-label="Remarks editor"
        ></div>

        <div class="mt-2 text-xs text-[#677489]">
            <template x-if="saving"><span>Saving…</span></template>
            <template x-if="!saving && lastSavedAt"><span>Saved <span x-text="lastSavedHuman"></span></span></template>
            <template x-if="error"><span class="text-red-600" x-text="error"></span></template>
        </div>
    </div>

    {{-- Recommendations --}}
    <div
        class="bg-white p-3 rounded-lg my-5"
        x-data='recommendationsEditor({
            route: "{{ route('api-assessment-recommendation') }}",
            initialContent: @json($existingRecommendations ?? ''),
            period_id: {{ $periodId }},
            lgu_id: {{ $lguId }},
            questionnaire_id: {{ $questionnaireId }},
            user_id: {{ auth()->user()->id }},
            init() { this.$refs.recommendations.innerHTML = this.initialContent }
        })'
    >
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Recommendations</h3>
        <small class="block ml-2 mb-5 text-[#677489]">Observations and Suggestions</small>

        <div
            id="recommendations"
            x-ref="recommendations"
            class="wysiwyg bg-white mt-5 h-60 overflow-auto border rounded-lg p-3 focus:outline-none"
            contenteditable="true"
            role="textbox"
            aria-label="Recommendations editor"
        ></div>

        <div class="mt-2 text-xs text-[#677489]">
            <template x-if="saving"><span>Saving…</span></template>
            <template x-if="!saving && lastSavedAt"><span>Saved <span x-text="lastSavedHuman"></span></span></template>
            <template x-if="error"><span class="text-red-600" x-text="error"></span></template>
        </div>
    </div>
</div>
