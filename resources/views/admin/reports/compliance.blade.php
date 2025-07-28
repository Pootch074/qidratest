@extends('layouts.reportmain')
@section('title', 'Compliance Monitoring')

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
                                <a href="{{ route('compliance-monitoring', ['period_id' => $bchjcb->id]) }}"
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
                                <a href="{{ route('compliance-monitoring', ['period_id' => request('period_id'), 'lgu_id' => $lgu->id]) }}"
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
                <span>Print Scoring</span>
                <img src="{{ asset('build/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
            </button>
        </div>


  <div id="print-section" class="max-h-[75vh] rounded-xl shadow">
    <table class="min-w-full border border-gray-300 text-sm text-left">
      <h2 class="text-center text-xl font-semibold mb-4">COMPLIANCE MONITORING</h2>
      <thead>
        <tr class=" text-center">
          <th class="border px-4 py-2 w-4/14 font-semibold">PARAMETER / FUNCTIONAL AREA</th>
          <th class="border px-4 py-2 w-2/14">WEIGHT PER INDICATOR</th>
          <th class="border px-4 py-2 w-2/14">PREVIOUS INDEX SCORE</th>
          <th class="border px-4 py-2 w-2/14">NEW INDEX SCORE</th>
          <th class="border px-4 py-2 w-2/14">STATUS</th>
          <th class="border px-4 py-2 w-2/14">Movement of Index Score</th>
        </tr>
      </thead>
      <tbody>


        @foreach ($sections as $vtyla)
            <tr class="bg-gray-100 font-semibold">
                <td colspan="6" class="border px-4 py-2 pl-30 text-left">
                    {{ chr(64 + $loop->iteration) }}. {{ $vtyla['parent']->name }}
                </td>
            </tr>

            @foreach ($vtyla['children'] as $child)
                <tr>
                    <td class="border px-4 py-2">{{ $loop->iteration }}. {{ $child->name }}</td>
                    <td class="border px-4 py-2 text-center font-semibold">
                        {{ number_format($weights[$child->id] ?? 0, 2) }}%
                    </td>
                    <td class="border px-4 py-2 text-center"></td>
                    <td class="border px-4 py-2 text-center"></td>
                    <td class="border px-4 py-2 text-center"></td>
                    <td class="border px-4 py-2 text-center"></td>
                </tr>
            @endforeach
        @endforeach


        

        <tr class="font-semibold text-center text-lg bg-gray-100">
          <td class="border px-4 py-2">TOTAL</td>
          <td class="border px-4 py-2"></td>
          <td class="border px-4 py-2"></td>
          <td class="border px-4 py-2 text-center">
</td>

          <td class="border px-4 py-2"></td>
          <td class="border px-4 py-2"></td>
        </tr>
        <tr class="font-semibold text-center text-lg bg-white">
          <td class="border px-4 py-2">NEW RATING</td>
          <td class="border px-4 py-2" colspan="2">LEVEL 2</td>
          <td class="border px-4 py-2" colspan="3"></td>
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