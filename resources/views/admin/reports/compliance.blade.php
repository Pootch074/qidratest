@extends('layouts.main')
@section('title', 'Compliance Monitoring')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl max-h-[80vh] overflow-y-auto">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-between mb-4">
            <div class="flex items-center gap-3">
                <button class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl">
                    2025 Monitoring Period
                    <img src="{{ asset('assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">

                </button>
                <button class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-3xl">
                    Davao City
                    <img src="{{ asset('assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">

                </button>
            </div>
            <button onclick="printScoring()"
                class="bg-[#DB0C16] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl cursor-pointer">
                <span>Print Scoring</span>
                <img src="{{ Vite::asset('resources/assets/icons/icon-print.png') }}" class="h-5 w-5" alt="Print Scoring">
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

        <!-- Section A -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">A. Administration and Organization</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">1. Vision, Mission, Goals and Organizational Structure</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">0.21</td>
          <td class="border px-4 py-2 text-center">0.21</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">2. Human Resource Management and Development</td>
          <td class="border px-4 py-2 text-center">11.00%</td>
          <td class="border px-4 py-2 text-center">0.23</td>
          <td class="border px-4 py-2 text-center">0.21</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.001</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">3. Public Financial Management</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
          <td class="border px-4 py-2 text-center">0.25</td>
          <td class="border px-4 py-2 text-center">0.20</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.02</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">4. Support Services</td>
          <td class="border px-4 py-2 text-center">8.00%</td>
          <td class="border px-4 py-2 text-center">0.24</td>
          <td class="border px-4 py-2 text-center">0.19</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>

        <!-- Section B -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">B. Program Management</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">1. Planning</td>
          <td class="border px-4 py-2 text-center">16.00%</td>
          <td class="border px-4 py-2 text-center">0.32</td>
          <td class="border px-4 py-2 text-center">0.32</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">2. Implementation</td>
          <td class="border px-4 py-2 text-center">9.00%</td>
          <td class="border px-4 py-2 text-center">0.24</td>
          <td class="border px-4 py-2 text-center">0.20</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.03</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">3. Monitoring and Reporting</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">0.14</td>
          <td class="border px-4 py-2 text-center">0.18</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.04</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">4. Case Management</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">0.39</td>
          <td class="border px-4 py-2 text-center">0.33</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.04</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">5. Presidential Care and Community-Based Center</td>
          <td class="border px-4 py-2 text-center">7.00%</td>
          <td class="border px-4 py-2 text-center">0.39</td>
          <td class="border px-4 py-2 text-center">0.33</td>
          <td class="border px-4 py-2 text-center">Increased</td>
          <td class="border px-4 py-2 text-center">0.04</td>
        </tr>

        <!-- Section C -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">C. Institutional Mechanisms</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">1. Functionality of Local Council for the Protection of Children</td>
          <td class="border px-4 py-2 text-center">16.00%</td>
          <td class="border px-4 py-2 text-center">0.15</td>
          <td class="border px-4 py-2 text-center">0.15</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">2. Functionality of Local Committee on Anti-trafficking and Violence Against Women and their Children (LCAT-VAWC)</td>
          <td class="border px-4 py-2 text-center">16.00%</td>
          <td class="border px-4 py-2 text-center">0.13</td>
          <td class="border px-4 py-2 text-center">0.13</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">3. Inter-office Collaboration</td>
          <td class="border px-4 py-2 text-center">16.00%</td>
          <td class="border px-4 py-2 text-center">0.12</td>
          <td class="border px-4 py-2 text-center">0.12</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">4. Support to Civil Society Organizations</td>
          <td class="border px-4 py-2 text-center">16.00%</td>
          <td class="border px-4 py-2 text-center">0.10</td>
          <td class="border px-4 py-2 text-center">0.15</td>
          <td class="border px-4 py-2 text-center">Sustained</td>
          <td class="border px-4 py-2 text-center">0.00</td>
        </tr>
        <tr class="font-semibold text-center text-lg bg-gray-100">
          <td class="border px-4 py-2">TOTAL</td>
          <td class="border px-4 py-2">100%</td>
          <td class="border px-4 py-2">2.52</td>
          <td class="border px-4 py-2">2.38</td>
          <td class="border px-4 py-2">Decreased</td>
          <td class="border px-4 py-2">-0.14</td>
        </tr>
        <tr class="font-semibold text-center text-lg bg-white">
          <td class="border px-4 py-2">NEW RATING</td>
          <td class="border px-4 py-2" colspan="2">LEVEL 2</td>
          <td class="border px-4 py-2" colspan="3">BETTER SERVICE DELIVERY</td>
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