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
