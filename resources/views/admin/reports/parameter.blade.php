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
          <th class="border px-4 py-2">DESCRIPTION</th>
          <th class="border px-4 py-2">RECOMMENDATION</th>
          <th class="border px-4 py-2">NEW INDEX SCORE</th>
        </tr>
      </thead>
      <tbody>

        <!-- Section A -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">A. Administration and Organization</td>
        </tr>
        <tr>
          <td class="border px-4 py-2 font-semibold">1. Vision, Mission, Goals and Organizational Structure</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">LSWDO VMGO</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
        </tr>
        <tr>
          <td class="border px-4 py-2 font-semibold">2. Human Resource Management and Development</td>
          <td class="border px-4 py-2 text-center">11.00%</td>
          <td class="border px-4 py-2 text-center">11.00%</td>
          <td class="border px-4 py-2 text-center">11.00%</td>
          <td class="border px-4 py-2 text-center">11.00%</td>
        </tr>
        <tr>
          <td class="border px-4 py-2 font-semibold">3. Public Financial Management</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
          <td class="border px-4 py-2 text-center">3.00%</td>
          <td class="border px-4 py-2 text-center">3.00%</td>
        </tr>
        <tr>
          <td class="border px-4 py-2 font-semibold">4. Support Services</td>
          <td class="border px-4 py-2 text-center">8.00%</td>
          <td class="border px-4 py-2 text-center">8.00%</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
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
                            body { background-color:pink;        font-family: Arial, sans-serif; padding: 20px; margin:0;}
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
