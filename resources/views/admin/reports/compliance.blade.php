@extends('layouts.main')
@section('title', 'Compliance Monitoring')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl max-h-[80vh] overflow-y-auto">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-between mb-4">
            <div class="flex items-center gap-3">
                
            
            
            
            <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl focus:outline-none">
                        {{ request('lgu_name', 'Select Period') }}
                        <img src="{{ Vite::asset('assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
                    </button>

                    <!-- <div x-show="open" @click.away="open = false"
                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <ul class="py-1 max-h-60 overflow-auto">
                            @foreach($lgus as $lgu)
                                <li>
                                    <a href="{{ route('compliance-monitoring', ['lgu_name' => $lgu->name]) }}"
                                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        {{ $lgu->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div> -->
                </div>
            



                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl focus:outline-none">
                        {{ request('lgu_name', 'Select LGU') }}
                        <img src="{{ Vite::asset('assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute z-50 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <ul class="py-1 max-h-60 overflow-auto">
                            @foreach($lgus as $lgu)
                                <li>
                                    <a href="{{ route('compliance-monitoring', ['lgu_name' => $lgu->name]) }}"
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
                <img src="{{ Vite::asset('assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
            </button>
        </div>


  <div id="print-section" class="max-h-[75vh] rounded-xl shadow">
    <table class="min-w-full border border-gray-300 text-sm text-left">
      <h2 class="text-center text-xl font-semibold mb-4">COMPLIANCE MONITORING</h2>
      <thead>
        <tr class=" text-center">
          <th class="border px-4 py-2 font-semibold">PARAMETER / FUNCTIONAL AREA</th>
          <th class="border px-4 py-2">WEIGHT PER INDICATOR</th>
          <th class="border px-4 py-2">PREVIOUS INDEX SCORE</th>
          <th class="border px-4 py-2">NEW INDEX SCORE</th>
          <th class="border px-4 py-2">STATUS</th>
          <th class="border px-4 py-2">Movement of Index Score</th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2" id="yui">A. {{ $trgfy->name }}</td>
        </tr>
        @foreach ($bcdg as $mklj)
        <tr>
          <td class="border px-4 py-2" id="zxc">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
        @endforeach


        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2" id="yui">B. {{ $ksuys->name }}</td>
        </tr>
        @foreach ($pitsv as $mklj)
        <tr>
          <td class="border px-4 py-2" id="zxc">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
        @endforeach

        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2" id="yui">C. {{ $dyeie->name }}</td>
        </tr>
        @foreach ($psisjs as $mklj)
        <tr>
          <td class="border px-4 py-2" id="zxc">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
        @endforeach

        <tr class="font-semibold text-center text-lg bg-gray-100">
          <td class="border px-4 py-2">TOTAL</td>
          <td class="border px-4 py-2"></td>
          <td class="border px-4 py-2"></td>
          <td class="border px-4 py-2"></td>
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