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
                        {{ $lgus->firstWhere('id', request('lgu_id'))?->name ?? 'Select LGU' }}

                        <img src="{{ asset('build/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <ul class="py-1 max-h-60 overflow-auto">
                            @foreach($lgus as $lgu)
                                <li>
                                    <a href="{{ route('parameter-report', ['lgu_id' => $lgu->id]) }}"
                                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $lgu->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>


                    
                </div>
            </div>
        </div>

  <div id="print-section" class="max-h-[75vh] rounded-xl shadow">
    <table class="min-w-full border border-gray-300 text-sm text-left">
        <h2 class="text-center text-xl font-semibold mb-4">SERVICE DELIVERY CAPACITY ASSESMENT RESULT</h2>
      <thead>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold">LGU</th>
          <th class="border px-4 py-2" colspan="4">TALAINGOD, DAVAO DEL NORTE</th>
        </tr>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold">Assesment Date</th>
          <th class="border px-4 py-2" colspan="4">26 NOVEMBER 2025</th>
        </tr>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold"></th>
          <th class="border px-4 py-2 font-semibold">LEVEL</th>
          <th class="border px-4 py-2">REMARKS</th>
          <th class="border px-4 py-2">RECOMMENDATIONS</th>
          <th class="border px-4 py-2">NEW INDEX SCORE</th>
        </tr>
      </thead>
      <tbody>
        

    @foreach ($sections as $vtyla)
        <tr class="bg-gray-100 font-semibold">
            <td colspan="5" class="border px-4 py-2 pl-30 text-left">
                {{ chr(64 + $loop->iteration) }}. {{ $vtyla['parent']->name }}
            </td>
        </tr>

        @foreach ($vtyla['children'] as $laoans)
            @php
                $grandchildren = $vtyla['grandchild']->where('parent_id', $laoans->id);
                $childLevels = $grandchildren->map(function ($child) use ($assessments) {
                    $assessment = $assessments->firstWhere('questionnaire_id', $child->id);
                    return $assessment && $assessment->questionnaireLevel ? $assessment->questionnaireLevel->level : null;
                })->filter(fn ($v) => $v !== null);

                $avgLevel = $childLevels->isNotEmpty() ? $childLevels->avg() : null;
            @endphp

            <tr>
                <td class="border px-4 py-2 font-semibold w-[400px]">{{ $loop->iteration }}. {{ $laoans->name }}</td>
                <td class="border px-4 py-2 text-center font-semibold">
                    {{ $avgLevel !== null ? number_format($avgLevel, 2) : '' }}
                </td>
                <td class="border px-4 py-2 text-center"></td>
                <td class="border px-4 py-2 text-center"></td>


                <td class="border px-4 py-2 text-center">
                    @if ($loop->parent->first && $loop->first)
                        {{ number_format($weightedLevelGroup1, 2) }}
                    @elseif ($loop->parent->iteration == 2 && $loop->first)
                        {{ number_format($weightedLevelGroup2, 2) }}
                    @endif
                </td>

            </tr>

            @foreach ($grandchildren as $popspsps)
                @php
                    $assessment = $assessments->first(fn ($a) => $a->questionnaire_id == $popspsps->id);


                @endphp
                <tr>
                    <td class="border px-4 py-2 pl-10">- {{ $popspsps->name }}</td>
                    <td class="border px-4 py-2 text-center">
                        {{ optional($assessment->questionnaireLevel)->level !== null ? number_format(optional($assessment->questionnaireLevel)->level, 2) : '' }}

                    </td>
                    <td class="border px-4 py-2 text-center">{{ $assessment->remarks ?? '' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $assessment->recommendations ?? '' }}</td>
                    <td class="border px-4 py-2 text-center" id="asds"></td>
                </tr>
            @endforeach
        @endforeach
    @endforeach










        <tr>
            <td class="border px-4 py-2 text-center font-bold align-middle" rowspan="2" colspan="2">
                FINAL RATING
            </td>
            <td class="border px-4 py-2 text-left" colspan="2">
                Information about the policies/guidelines on the implementation of LSWDO's programs and services, through manuals, citizen’s charter and the likes are available and accessible for use of staff and their clients but are not yet in the form of manual
            </td>
            <td class="border px-4 py-2 text-center font-bold bg-green-200 align-middle" rowspan="2" style="width: 80px;">
                2.38
            </td>
        </tr>
        <tr>
            <td class="border px-4 py-2 text-center font-semibold" colspan="2">
                Level 2
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
            const printWindow = window.open('', '', 'width=auto,height=auto,');
            printWindow.document.write(`
                <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; margin:0;}
                            table { width: 100%; border-collapse: collapse; }
                            th { border: 1px solid #ccc; padding: 8px; text-align: center; }
                            td { border: 1px solid #ccc; padding: 8px; }
                            th { background-color: #e6f4ea; }
                            h2 { text-align: center; }
                        </style>
                    </head>
                    ${printContent}
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        }
    </script>
@endsection
