
<h2 class="text-[#94969B] font-bold text-xl mb-5">{{ $parent->name }}</h2>
<div id="assessment-questionnaires" class="bg-[#EEEFF1] p-[30px]">

    <h2>
        <span class="bg-[#2E3192] text-l inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">{{ $child->reference_number }}</span>
        <span class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">{{ $child->name }}</span>
    </h2>

    <hr class="my-5 border-[#CDCFD2] border-1">

    <div class="bg-white p-3 rounded-lg my-5">
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Means of Verification</h3>

        <div class="options-container mt-5">
            @foreach ($means as $mean)
            @if (auth()->user()->user_type == 1)
                <div
                    class="option"
                >
            @else
                <div
                    x-data="movToggle({
                        route: '{{ route('api-assessment-mov') }}',
                        user_id: '{{ auth()->user()->id }}',
                        period_id: '{{ $periodId }}',
                        questionnaire_id: '{{ $questionnaireId }}',
                        lgu_id: '{{ $lguId }}',
                        mov_id: {{ $mean->id }},
                        initialChecked: {{ in_array($mean->id, $checkedMeans ?? []) ? 'true' : 'false' }}
                    })"
                    @click="toggle()"
                    :class="checked ? 'option selected' : 'option'"
                    class="option"
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
                <div
                    class="option"
                >
            @else
                <div
                    x-data="movToggle({
                        route: '{{ route('api-assessment-mov') }}',
                        user_id: '{{ auth()->user()->id }}',
                        period_id: '{{ $periodId }}',
                        questionnaire_id: '{{ $questionnaireId }}',
                        lgu_id: '{{ $lguId }}',
                        mov_id: 0,
                        initialChecked: {{ in_array(0, $checkedMeans ?? []) ? 'true' : 'false' }}
                    })"
                    @click="toggle()"
                    :class="checked ? 'option selected' : 'option'"
                    class="option"
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


    <div class="bg-white p-3 rounded-lg my-5">
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Service Performance Level</h3>

        <div class="options-container mt-5">
            @foreach ($levels as $level)

                @if (auth()->user()->user_type == 1)
                    <div
                        class="option"
                    >
                @else
                    <div
                        x-data="levelToggle({
                            route: '{{ route('api-assessment-level') }}',
                            user_id: '{{ auth()->user()->id }}',
                            period_id: '{{ $periodId }}',
                            questionnaire_id: '{{ $questionnaireId }}',
                            lgu_id: '{{ $lguId }}',
                            level_id: {{ $level->id }},
                            initialChecked: {{ $selectedLevelId == $level->id ? 'true' : 'false' }}
                        })"
                        x-ref="levelOption"
                        @click="toggle(); console.log('checked:', checked)"
                        :class="{ 'option selected': checked, 'option': !checked }"
                        class="option"
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
                        <p><b>
                            {{ $level->level > 0 ? "Level $level->level" : "Low" }}
                        </b></p>
                        <small>{!! $level->remarks !!}</small>
                    </div>
                </div>
            @endforeach

            {{-- Optional: Not Applicable --}}
            @if (auth()->user()->user_type == 1)
                <div
                    class="option"
                >
            @else
                <div
                    x-data="levelToggle({
                        route: '{{ route('api-assessment-level') }}',
                        user_id: '{{ auth()->user()->id }}',
                        period_id: '{{ $periodId }}',
                        questionnaire_id: '{{ $questionnaireId }}',
                        lgu_id: '{{ $lguId }}',
                        level_id: 9,
                        initialChecked: {{ $selectedLevelId == 9 ? 'true' : 'false' }}
                    })"
                    @click="toggle()"
                    :class="checked ? 'option selected' : 'option'"
                    class="option"
                >
            @endif
                <input
                    type="radio"
                    x-model="checked"
                    class="custom-checkbox"
                    value="9"
                    name="level_option"
                />
                <div class="option-text">
                    <p><b>Not Applicable</b></p>
                    <small>Indicator not applicable to LGU</small>
                </div>
            </div>
        </div>
    </div>

    <div
        class="bg-white p-3 rounded-lg my-5"
        x-data="remarksEditor({
            route: '{{ route('api-assessment-remarks') }}',
            initialContent: `{!! addslashes($existingRemarks ?? '') !!}`,
            period_id: {{ $periodId }},
            lgu_id: {{ $lguId }},
            questionnaire_id: {{ $questionnaireId }},
            user_id: {{ auth()->user()->id }}
            })"
        x-init="init"
    >
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Remarks</h3>
        <small class="block ml-2 mb-5 text-[#677489]">Observations and Suggestions</small>

        <!-- Make it contenteditable and reference it from Alpine -->
        <div
            id="remarks"
            x-ref="remarks"
            class="wysiwyg bg-white mt-5 h-60 overflow-auto border rounded-lg p-3 focus:outline-none"
            contenteditable="true"
            role="textbox"
            aria-label="Remarks editor"
        ></div>

        <!-- Optional tiny status indicator -->
        <div class="mt-2 text-xs text-[#677489]"    >
            <template x-if="saving"><span>Saving…</span></template>
            <template x-if="!saving && lastSavedAt"><span>Saved <span x-text="lastSavedHuman"></span></span></template>
            <template x-if="error"><span class="text-red-600" x-text="error"></span></template>
        </div>

        {{-- You can remove the manual Save button if you no longer want it --}}
        {{-- @if (auth()->user()->user_type > 1)
            <a href="#" @click.prevent="saveNow" class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-2 mt-3 text-white rounded-xl">
                Save
            </a>
        @endif --}}
    </div>

    <div
        class="bg-white p-3 rounded-lg my-5"
        x-data="recommendationsEditor({
            route: '{{ route('api-assessment-recommendation') }}',
            initialContent: `{!! addslashes($existingRecommendations ?? '') !!}`,
            period_id: {{ $periodId }},
            lgu_id: {{ $lguId }},
            questionnaire_id: {{ $questionnaireId }},
            user_id: {{ auth()->user()->id }}
        })"
        x-init="init"
    >
        <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Recommendations</h3>
        <small class="block ml-2 mb-5 text-[#677489]">Observations and Suggestions</small>

        <!-- Make it contenteditable and reference it from Alpine -->
        <div
            id="recommendations"
            x-ref="recommendations"
            class="wysiwyg bg-white mt-5 h-60 overflow-auto border rounded-lg p-3 focus:outline-none"
            contenteditable="true"
            role="textbox"
            aria-label="Recommendations editor"
        ></div>

        <!-- Tiny status indicator -->
        <div class="mt-2 text-xs text-[#677489]">
            <template x-if="saving"><span>Saving…</span></template>
            <template x-if="!saving && lastSavedAt"><span>Saved <span x-text="lastSavedHuman"></span></span></template>
            <template x-if="error"><span class="text-red-600" x-text="error"></span></template>
        </div>

        {{-- Manual save no longer needed; keep for fallback if you want --}}
        {{-- @if (auth()->user()->user_type > 1)
            <a href="#" @click.prevent="saveNow" class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-2 mt-3 text-white rounded-xl">
                Save
            </a>
        @endif --}}
    </div>
</div>
