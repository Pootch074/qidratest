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
            </div>

            <button onclick="printScoring()"
                class="bg-[#DB0C16] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl cursor-pointer">
                <span>Print</span>
                <img src="{{ asset('build/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
            </button>
        </div>

  <div id="print-section" class="max-h-[75vh] rounded-xl shadow">
    <table class="min-w-full border border-gray-300 text-sm text-left">
        <h2 class="text-center text-xl font-semibold mb-4">SERVICE DELIVERY CAPACITY ASSESMENT RESULT</h2>
      <thead>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold">LGU</th>
          <th class="border px-4 py-2" colspan="5">{{ $lgus->firstWhere('id', request('lgu_id'))?->name ?? 'No LGU Selected' }}</th>
        </tr>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold">Assesment Date</th>
          <th class="border px-4 py-2" colspan="5">{{ $cksu->firstWhere('id', request('period_id'))?->name ?? 'No Period Selected' }}</th>
        </tr>
        <tr class=" text-center">
          <th class="border px-4 py-2 w-4/14 font-semibold"></th>
          <th class="border px-4 py-2 w-2/14 font-semibold">LEVEL</th>
          <th class="border px-4 py-2 w-2/14 font-semibold">WEIGHT</th>
          <th class="border px-4 py-2 w-2/14">REMARKS</th>
          <th class="border px-4 py-2 w-2/14">RECOMMENDATIONS</th>
          <th class="border px-4 py-2 w-2/14">NEW INDEX SCORE</th>
        </tr>
      </thead>
      <tbody>
        

    @foreach ($sections as $vtyla)
        <!-- A. Administration and Organization -->
        <tr class="bg-gray-100 font-semibold">
            <td colspan="6" class="border px-4 py-2 pl-30 text-left">
                {{ chr(64 + $loop->iteration) }}. {{ $vtyla['parent']->name }}
            </td>
        </tr>
    
        @foreach ($vtyla['children'] as $child)
            <!-- 1. Vision, Mision, Goals, and Organizational Structure -->
            @php
            $grandchildren = $vtyla['grandchild']->where('parent_id', $child->id);

            $levels = $grandchildren->map(function ($g) {
                return optional($g->assessment->level)->level;
            })->filter(); // remove nulls

            $averageLevel = $levels->count() ? number_format($levels->avg(), 2) : '0.00';
            @endphp
            <tr>
                <td class="border px-4 py-2 font-semibold">{{ $loop->iteration }}. {{ $child->name }}</td>
                <td class="border px-4 py-2 font-semibold text-center">{{ $averageLevel }}</td>
                <td class="border px-4 py-2 text-center"></td>
                <td class="border px-4 py-2 text-center"></td>
                <td class="border px-4 py-2 text-center"></td>
                <td class="border px-4 py-2 text-center"></td>
            </tr>

            @foreach ($grandchildren as $grandchild)
            <!-- LSWDO's Vision, Mission and Goals -->
                <tr>
                    <td class="border px-4 py-2 pl-10">{{ $grandchild->name }}</td>
                    <td class="border px-4 py-2 text-center">
                        @if ($grandchild->assessment && $grandchild->assessment->level)
                            {{ number_format($grandchild->assessment->level->level, 2) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="border px-4 py-2 text-center"></td>

                    <td class="border px-4 py-2 text-center">{{ $grandchild->remarks ?? '' }}</td>
                    <td class="border px-4 py-2 text-center">{{ $grandchild->recommendations ?? '' }}</td>
                    
                </tr>
            @endforeach
        @endforeach
    @endforeach

        <tr>
            <td class="border px-4 py-2 text-center font-bold align-middle" rowspan="2" colspan="2">
                FINAL RATING
            </td>
            <td class="border px-4 py-2 text-left" colspan="3">
                Information about the policies/guidelines on the implementation of LSWDO's programs and services, through manuals, citizen’s charter and the likes are available and accessible for use of staff and their clients but are not yet in the form of manual
            </td>
            <td class="border px-4 py-2 text-center font-bold align-middle" rowspan="2" style="width: 80px;">
                
            </td>
        </tr>
        <tr>
            <td class="border px-4 py-2 text-center font-semibold" colspan="3">
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
