<h2 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21] mb-5">Item Navigation</h2>

<div class="nav-container bg-[#EEEFF1] p-4 rounded-xl">

    @php $parent = $child->parent_id; @endphp
    @foreach($references as $ref)

        @if($parent != $ref['parent_id'])
            <hr class="divider" />
            @php $parent = $ref['parent_id'] @endphp
        @endif

        <a href="{{ route('assessment-management')}}?ref={{ $ref['id'] }}" class="nav-item {{ $ref['id'] == $child->id ? 'active' : '' }}" data-questionnaire="{{ $ref['id'] }}">
            <div class="ref">{{ $ref['reference_number'] }}</div>
            <div class="status {!! $ref['status'] !!}"></div>
        </a>
    @endforeach
</div>

@if (auth()->user()->user_type > 1 && $assessmentStatus == 'completed')
<div x-data="{ showEndorseModal: false }">
    <!-- Endorse Button -->
    <a href="#" @click.prevent="showEndorseModal = true"
       class="bg-white inline-flex items-center gap-2 border-2 border-[#2E3192] text-[#2E3192] px-4 py-2 mt-3 text-xl rounded-[10px] hover:bg-[#2E3192] hover:text-white transition duration-300 ease-in-out">
        <span>Endorse Assessment</span>
    </a>

    <!-- Modal -->
    <div x-show="showEndorseModal"
         x-cloak
         x-transition
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div @click.away="showEndorseModal = false"
             class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full">
            <h2 class="text-xl font-semibold mb-4">Confirm Endorsement</h2>
            <p class="mb-4 text-gray-700">Are you sure you want to endorse this assessment?</p>
            <div class="flex justify-end gap-3">
                <button @click="showEndorseModal = false"
                        class="px-4 py-2 text-gray-600 bg-gray-200 rounded hover:bg-gray-300">
                    Cancel
                </button>
                <form method="POST" action="#">
                    @csrf
                    <input type="hidden" name="assessment_id" value="#">
                    <button type="submit"
                            class="px-4 py-2 bg-[#2E3192] text-white rounded hover:bg-[#1f236e] transition">
                        Confirm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif