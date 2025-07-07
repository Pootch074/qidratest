@extends('layouts.main')
@section('title', 'Compliance Monitoring')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-between mb-4">
            <button class="bg-[#2E3192] inline-flex items-center gap-2 border px-4 py-3 text-white rounded-xl">
                2025 Monitoring Period
                <img src="{{ Vite::asset('resources/assets/icons/icon-sidebar-down.svg') }}" alt="Toggle">
            </button>
            @include('admin.reports.create')
        </div>

          <h2 class="text-center text-xl font-semibold mb-4">COMPLIANCE MONITORING</h2>

  <div class="overflow-x-auto rounded-xl shadow">
    <table class="min-w-full border border-gray-300 text-sm text-left">
      <thead class="bg-green-100">
        <tr>
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
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">2. Human Resource Management and Development</td>
          <td class="border px-4 py-2">11.00%</td>
          <td class="border px-4 py-2">11.00%</td>
          <td class="border px-4 py-2">11.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.001</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">3. Public Financial Management</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">3.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.02</td>
        </tr>
        <tr class="bg-red-100">
          <td class="border px-4 py-2">4. Support Services</td>
          <td class="border px-4 py-2">8.00%</td>
          <td class="border px-4 py-2">8.00%</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>

        <!-- Section B -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">B. Program Management</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">1. Planning</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">2. Implementation</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">9.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.03</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">3. Monitoring and Reporting</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.04</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">4. Case Management</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.04</td>
        </tr>
        <tr class="bg-blue-100">
          <td class="border px-4 py-2">5. Presidential Care and Community-Based Center</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">7.00%</td>
          <td class="border px-4 py-2">Increased</td>
          <td class="border px-4 py-2">0.04</td>
        </tr>

        <!-- Section C -->
        <tr class="bg-gray-100 font-semibold">
          <td colspan="6" class="border px-4 py-2">C. Institutional Mechanisms</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">1. Functionality of Local Council for the Protection of Children</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">2. Functionality of Local Committee on Anti-trafficking and Violence Against Women and their Children (LCAT-VAWC)</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">3. Inter-office Collaboration</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
        <tr>
          <td class="border px-4 py-2">4. Support to Civil Society Organizations</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">16.00%</td>
          <td class="border px-4 py-2">Sustained</td>
          <td class="border px-4 py-2">0.00</td>
        </tr>
      </tbody>
    </table>
  </div>

    </div>

@endsection

@section('script')
    @include('admin.periods.script')
@endsection