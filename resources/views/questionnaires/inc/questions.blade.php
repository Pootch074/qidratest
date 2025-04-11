
<h2 class="text-[#94969B] font-bold text-xl mb-5">Vision, Mission, Goals and Organizational Structure</h2>
<div class="bg-[#EEEFF1] p-[30px]">

    <h2>
        <span class="bg-[#2E3192] text-l inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">AO 1.a</span>
        <span class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">LSDWO's Vision, Mission, and Goals</span>
    </h2>

<hr class="my-5 border-[#CDCFD2] border-1">

<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Means of Verification</h3>

    <div class="options-container mt-5">
        @for($i = 0; $i < 3; $i++)
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
                <span>Option {{ $i }}</span>
            </div>
        @endfor
    </div>

</div>


<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Service Performance Level</h3>


    <div class="options-container mt-5" x-data="{ checked: null }">
        @for($i = 0; $i < 3; $i++)
            <div
                @click="checked = {{ $i }}"
                :class="checked === {{ $i }} ? 'option selected' : 'option'"
            >
                <input
                    type="radio"
                    :value="{{ $i }}"
                    name="options"
                    x-model="checked"
                    class="custom-checkbox"
                />
                <div class="option-text">
                    <p>Option {{ $i }}</p>
                    <small>Lorem ipsum dolor sit amet.</small>
                </div>
            </div>
        @endfor
    </div>
</div>

<div class="bg-white p-3 rounded-lg my-5">
    <h3 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Remarks</h3>
    <small class="block ml-2 text-[#677489]">Observations and Suggestions</small>

    <table class="min-w-full w-full border-separate border-spacing-0 my-5 ">
        <tbody class="bg-white">
        <tr class="border-b border border-gray-400">
            <td class="px-6 py-4 text-sm text-gray-700">Row 1: Lorem ipsum</td>
        </tr>
        <tr class="border-b border border-gray-400">
            <td class="px-6 py-4 text-sm text-gray-700">Row 2: Dolor sit amet</td>
        </tr>
        <tr class="border-b border border-gray-400">
            <td class="px-6 py-4 text-sm text-gray-700">Row 3: Consectetur</td>
        </tr>
        <tr class="border-b border border-gray-400">
            <td class="px-6 py-4 text-sm text-gray-700">Row 4: Adipiscing elit</td>
        </tr>
        <tr class="border-b border border-gray-400">
            <td class="px-6 py-4 text-sm text-gray-700">Row 5: Sed do eiusmod</td>
        </tr>
        </tbody>
    </table>
</div>
</div>
