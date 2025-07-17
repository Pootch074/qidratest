@extends('layouts.main')
@section('title', 'Parameter Result')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl max-h-[80vh] overflow-y-auto">
        {{-- @include('admin.reports.search') --}}
        
        <div class="flex justify-between mb-4">
            <button class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
                2025 Monitoring Period
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
            </button>
             <button onclick="printScoring()"
                class="bg-[#DB0C16] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl cursor-pointer">
                <span>Print Scoring</span>
                <img src="{{ Vite::asset('resources/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
            </button>
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
        <tr class="bg-gray-100 font-semibold">
          <td colspan="5" class="border px-4 py-2 pl-40 text-left">A. {{ $sdfty->name }}</td>
        </tr>
        @foreach ($mcyla as $mklj)
        <tr>
          <td class="border px-4 py-2 font-semibold">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
        @endforeach

        <tr class="bg-gray-100 font-semibold">
          <td colspan="5" class="border px-4 py-2 pl-40 text-left">B. {{ $dsdsaa->name }}</td>
        </tr>
        @foreach ($errtt as $mklj)
        <tr>
          <td class="border px-4 py-2 font-semibold">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
        @endforeach


        <tr class="bg-gray-100 font-semibold">
          <td colspan="5" class="border px-4 py-2 pl-40 text-left">C. {{ $skdud->name }}</td>
        </tr>
        @foreach ($nchusus as $mklj)
        <tr>
          <td class="border px-4 py-2 font-semibold">{{ $mklj->weight }}. {{ $mklj->name }}</td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
          <td class="border px-4 py-2 text-center"></td>
        </tr>
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
