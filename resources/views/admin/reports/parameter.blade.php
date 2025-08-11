@extends('layouts.main')
@section('title', 'Parameter Result')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl max-h-[80vh] overflow-y-auto">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-between mb-4">
          <div class="flex items-center gap-3">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                    class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl focus:outline-none">
                    {{ $cksu->firstWhere('id', request('period_id'))?->name ?? 'Select Period' }}
                    <img src="{{ asset('build/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                    <ul class="py-1 max-h-60 overflow-auto">
                        @foreach($cksu as $bchjcb)
                            <li>
                                <a href="{{ route('parameter-report', ['period_id' => $bchjcb->id]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $bchjcb->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @php
                $hasPeriod = request('period_id') !== null;
            @endphp
                
            <div x-data="{ open: false }" class="relative">
                <button 
                    @click="open = {{ $hasPeriod ? '!open' : 'false' }}"
                    :class="{ 'opacity-50 cursor-not-allowed': {{ $hasPeriod ? 'false' : 'true' }} }"
                    class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl focus:outline-none"
                    {{ $hasPeriod ? '' : 'disabled' }}>
                    {{ $lgus->firstWhere('id', request('lgu_id'))?->name ?? 'Select LGU' }}
                    <img src="{{ asset('build/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                    <ul class="py-1 max-h-60 overflow-auto">
                        @foreach($lgus as $lgu)
                            <li>
                                <a href="{{ route('parameter-report', ['period_id' => request('period_id'), 'lgu_id' => $lgu->id]) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    {{ $lgu->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

                
            </div>

            <button onclick="printScoring()"
                class="bg-[#DB0C16] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl cursor-pointer">
                <span>Print</span>
                <img src="{{ asset('build/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
            </button>
        </div>

  <div id="print-section" class="max-h-[75vh] rounded-xl shadow">
    <style>
    table, th, td {
        border: 1px solid #BFBFBF !important;
    }
</style>
    @php
        $lguId = request('lgu_id');
    @endphp
    <table class="min-w-full text-sm text-left">
        <h2 class="text-center text-xl font-semibold mb-4">SERVICE DELIVERY CAPACITY ASSESMENT RESULT</h2>
      <thead>
        <tr class="text-center" id="lgu-header">
          <th class="border px-4 py-2 font-semibold">LGU</th>
          <th class="border px-4 py-2" colspan="4">{{ $lgus->firstWhere('id', request('lgu_id'))?->name ?? 'No LGU Selected' }}</th>
        </tr>
        <tr class="text-center" id="assessment-header">
            <th class="border px-4 py-2 font-semibold">Assesment Date</th>
            <th class="border px-4 py-2" colspan="4">
                @if($assessment?->assessment_start_date)
                    {{ \Carbon\Carbon::parse($assessment->assessment_start_date)->format('F j, Y') }}
                @endif
            </th>
        </tr>
        <tr class=" text-center" id="label-header">
          <th class="border px-4 py-2 w-3/10 font-semibold"></th>
          <th class="border px-4 py-2 w-1/10 font-semibold">LEVEL</th>
          <!-- <th class="border px-4 py-2 w-1/10 font-semibold border-red-500">WEIGHT</th> -->
          <th class="border px-4 py-2 w-2/10">REMARKS</th>
          <th class="border px-4 py-2 w-2/10">RECOMMENDATIONS</th>
          <th class="border px-4 py-2 w-1/10">NEW INDEX SCORE</th>
        </tr>
      </thead>
      <tbody>
        

    @foreach ($sections as $vtyla)
        <!-- A. Administration and Organization -->
        <tr class="font-semibold" style="background-color: #EAEAEA;" id="parent-header">
            <td colspan="5" class="border px-4 py-2 pl-30 text-left">
                {{ chr(64 + $loop->iteration) }}. {{ $vtyla['parent']->name }}
            </td>
        </tr>
    
        @foreach ($vtyla['children'] as $child)
            <!-- 1. Vision, Mision, Goals, and Organizational Structure -->
            @php
                $grandchildren = $vtyla['grandchild']->where('parent_id', $child->id);

                $levels = $grandchildren
                    ->map(fn($g) => $g->assessment?->questionnaireLevel?->level ?? null)
                    ->filter(fn($lvl) => $lvl !== null && (float)$lvl !== 9.0);

                $averageLevel = $levels->count() ? number_format($levels->avg(), 2) : null;

                $newIndexScore = ($child->new_index_score && (float)$child->new_index_score !== 0.0)
                    ? number_format($child->new_index_score, 2)
                    : null;
            @endphp

            <tr id="children-header">
                <td class="border px-4 py-2 font-semibold">{{ $loop->iteration }}. {{ $child->name }}</td>
                <td class="border px-4 py-2 font-semibold text-center">
                    {{ $lguId && $averageLevel !== null ? $averageLevel : '' }}
                </td>

                <td class="border px-4 py-2 text-center"></td>
                <td class="border px-4 py-2 text-center"></td>
                <td class="border-b ... text-center font-semibold text-[30px]">
                    {{ $lguId && $newIndexScore !== null ? $newIndexScore : '' }}
                </td>
            </tr>


            @foreach ($grandchildren as $grandchild)
                @php
                    $level = optional($grandchild->assessment?->questionnaireLevel)->level;
                @endphp
                <tr id="grandchildren-header">
                    <td class="border px-4 py-2 pl-10">{{ $grandchild->name }}</td>
                    
                    <td class="border px-4 py-2 text-center">
                        {{ $level !== null ? (number_format($level, 2) == '9.00' ? 'N/A' : number_format($level, 2)) : '' }}
                    </td>

                    @php
                        $remarks = \App\Models\AssessmentRemark::where('questionnaire_id', $grandchild->id)
                            ->where('period_id', request('period_id'))
                            ->where('lgu_id', request('lgu_id'))
                            ->value('remarks');

                        $recommendations = \App\Models\AssessmentRecommendation::where('questionnaire_id', $grandchild->id)
                            ->where('period_id', request('period_id'))
                            ->where('lgu_id', request('lgu_id'))
                            ->value('recommendations');
                    @endphp

                    <td class="border px-4 py-2 text-center">
                        {{ trim(strip_tags($remarks)) }}
                    </td>
                    <td class="border px-4 py-2 text-center">
                        {{ trim(strip_tags($recommendations)) }}
                    </td>
                </tr>
            @endforeach

        @endforeach
    @endforeach

        <tr>
            <td class="border px-4 py-2 text-center font-bold align-middle text-[20px]" id="final-rating" rowspan="2" colspan="2">
                FINAL RATING
            </td>
            <td class="border px-4 py-2 text-left text-[15px]" colspan="2">
                {{ $lguId ? $interpretation : ''}}
            </td>
            <td class="border px-4 py-2 text-center font-bold align-middle text-[40px]" id="totalnewindexscore" rowspan="2" style="width: 80px;">
                {{ $lguId ? number_format($totalNewIndexScore, 2) : '' }}
            </td>
        </tr>
        <tr>
            <td class="border px-4 py-2 text-center font-semibold text-[20px]" id="level" colspan="2">
                {{ $lguId ? $paramLevel : ''}}
            </td>
        </tr>

      </tbody>
    </table>
  </div>
    </div>

@endsection

@section('script')
    @include('admin.periods.script')
    <script>
        function printScoring() {
            const printContent = document.getElementById('print-section').innerHTML;
            const printWindow = window.open('', '', 'width=auto,height=auto');
            printWindow.document.write(`
                <html>
                    <head>
                        <style>
                            @media print {
                                body {
                                    font-family: Arial, sans-serif;
                                    font-size: 12px;
                                    padding: 0;
                                    margin: 0;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                }
                                #lgu-header, #assessment-header {
                                    text-align: center;
                                }
                                #parent-header {
                                    background-color: #EAEAEA;
                                    font-weight: bold;
                                }

                                #parent-header td {
                                    background-color: #EAEAEA;
                                    padding-left: 30px;
                                }
                                #children-header {
                                    font-weight: semibold;
                                }
                                #children-header td:nth-child(2) {
                                    text-align: center;
                                }
                                #children-header td:nth-child(5) {
                                    text-align: center;
                                }
                                #grandchildren-header td:nth-child(1) {
                                    padding-left: 20px;
                                }
                                #grandchildren-header td:nth-child(2) {
                                    text-align: center;
                                }
                                #grandchildren-header td:nth-child(n+3):nth-child(-n+5) {
                                    text-align: center;
                                }
                                th, td {
                                    padding: 6px 8px;
                                    vertical-align: middle;
                                    font-size: 12px;
                                }
                                th {
                                    font-weight: bold;
                                }
                                h2, h3 {
                                    text-align: center;
                                    margin-bottom: 0.5em;
                                }
                                #final-rating {
                                text-align: center;}

                                #level {
                                    text-align: center;
                                    font-weight: semibold;
                                }
                                #totalnewindexscore {
                                    text-align: center; 
                                    font-size: 20px;
                                    font-weight: bold;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${printContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>
@endsection

