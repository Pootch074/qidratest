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

<a href="#" @click.prevent="save" class="endorse-button">
                Endorse
            </a>
<style>
.endorse-button {
    display: inline-flex;
    align-items: center;
    justify-content: center; /* To center the text horizontally */
    gap: 0.5rem; /* Equivalent to gap-2, adjust if needed */
    border: 1px solid #2E3192; /* Equivalent to border border-[#2E3192] */
    padding: 0.5rem 1rem; /* Equivalent to px-4 py-2, adjust as needed */
    margin-top: 0.75rem; /* Equivalent to mt-3, adjust as needed */
    color: #2E3192; /* Equivalent to text-[#2E3192] */
    font-weight: 700; /* Equivalent to text-bold, assuming bold corresponds to 700 */
    border-radius: 0.75rem; /* Equivalent to rounded-xl, adjust for a more rounded look */
    text-decoration: none; /* Remove underline from link */
    min-width: 100px; /* Adjust as needed to match button width */
    height: 40px; /* Adjust as needed to match button height */
    font-size: 1rem; /* Adjust font size if needed */
}
</style>