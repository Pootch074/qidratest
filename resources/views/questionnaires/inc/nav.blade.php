<h2 class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21] mb-5">Item Navigation</h2>

<div class="nav-container bg-[#EEEFF1] p-4 rounded-xl">
    @for($i = 0; $i < 2; $i++)
        @php
            $status = rand(0, 1) ? 'complete' : 'incomplete';
        @endphp
        <a href="#" class="nav-item">
            <div class="ref">AO 2.a.{{ $i }}</div>

            <div class="status {{ $status }}"></div>
        </a>
    @endfor

    <hr class="divider" />

    @for($i = 0; $i < 17; $i++)
        @php
            $status = rand(0, 1) ? 'complete' : 'incomplete';
        @endphp
        <a href="#" class="nav-item">
            <div class="ref">AO 2.b.{{ $i }}</div>

            <div class="status {{ $status }}"></div>
        </a>
    @endfor

    <hr class="divider" />


    @for($i = 0; $i < 7; $i++)
        @php
            $status = rand(0, 1) ? 'complete' : 'incomplete';
        @endphp
        <a href="#" class="nav-item">
            <div class="ref">AO 2.c.{{ $i }}</div>

            <div class="status {{ $status }}"></div>
        </a>
    @endfor
</div>
