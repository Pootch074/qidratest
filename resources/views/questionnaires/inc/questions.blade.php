
<h2 class="text-[#94969B] font-bold text-xl mb-5">{{ $parent->name }}</h2>
<div class="bg-[#EEEFF1] p-[30px]">

    <h2>
        <span class="bg-[#2E3192] text-l inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">{{ $child->reference_number }}</span>
        <span class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">{{ $child->name }}</span>
    </h2>

<hr class="my-5 border-[#CDCFD2] border-1">

<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Means of Verification</h3>

    <div class="options-container mt-5">
        @foreach ($means as $mean)
            <div
                x-data="{ checked: false }"
                @click="checked = !checked"
                :class="checked ? 'option selected' : 'option'"
            >
                <input
                    type="checkbox"
                    x-model="checked"
                    class="custom-checkbox"
                />
                <span>{!! $mean->means !!}</span>
            </div>
        @endforeach
    </div>

</div>


<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Service Performance Level</h3>


    <div class="options-container mt-5" x-data="{ checked: null }">
        @foreach ($levels as $level)
        <div
            @click="checked = {{ $level->level }}"
            :class="checked === {{ $level->level }} ? 'option selected' : 'option'"
        >
            <input
                type="radio"
                :value="{{ $level->level }}"
                name="options"
                x-model="checked"
                class="custom-checkbox"
            />
            <div class="option-text">
                @if ($level->level > 0)
                    <p><b>Level {{ $level->level }}</b></p>
                @else
                    <p><b>Low</b></p>
                @endif
                <small>{!! $level->remarks !!}</small>
            </div>
        </div>
        @endforeach
        <div
            @click="checked = 9"
            :class="checked === 9 ? 'option selected' : 'option'"
        >
            <input
                type="radio"
                :value="9"
                name="options"
                x-model="checked"
                class="custom-checkbox"
            />
            <div class="option-text">
                <p><b>Not Applicable</b></p>
                <small>Indicator not applicable to LGU</small>
            </div>
        </div>
    </div>
</div>

<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Remarks</h3>
    <small class="block ml-2 mb-5 mb text-[#677489]">Observations and Suggestions</small>

    <div id="remarks" class="wysiwyg bg-white mt-5 h-60"></div>
</div>
</div>
