@extends('layouts.main')
@section('title', 'Parameter Result')

@section('content')

    <div x-data="pTable" x-init="fetchP()" class="container mx-auto p-4 bg-white rounded-xl">
        {{-- @include('admin.reports.search') --}}
        <div class="flex justify-end mb-4">
            @include('admin.reports.create')
        </div>

        <div class="text-xl font-bold mb-4 text-[#667085]">
            <h1 class="text-center">SERVICE DELIVERY CAPACITY ASSESMENT RESULT</h1>
        </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-center rtl:text-right text-gray-700">
                    <thead>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 w-2/6 font-medium text-gray-900 whitespace-nowrap">LGU
                            </th>
                            <th scope="row" class="border-1 px-6 py-4 w-4/6 font-medium text-gray-900 whitespace-nowrap">TALAINGOD, DAVAO DEL NORTE
                            </th>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">Assesment Date
                            </th>
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">26 NOVEMBER 2024
                            </th>
                        </tr>
                    </thead>
                </table>
                <table class="w-full text-sm text-left rtl:text-right text-gray-700">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-200">
                        <tr class="bg-white text-center uppercase border-b border-gray-200">
                            <td class="border px-6 py-4 w-2/6 font-medium text-gray-900 whitespace-nowrap"></td>
                            <td class="border px-6 py-4 w-1/6">Level</td>
                            <td class="border px-6 py-4 w-2/6">Description</td>
                            <td class="border px-6 py-4 w-1/6">New Index Score</td>
                        </tr>
                        <tr>
                            <th scope="col" class="px-6 py-3 w-2/6">A. Administration and Organization</th>
                            <th scope="col" class="px-6 py-3 w-1/6"></th>
                            <th scope="col" class="px-6 py-3 w-2/6"></th>
                            <th scope="col" class="px-6 py-3 w-1/6"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                1. Vision, Mission, Goals and Organizational Structure
                            </th>
                            <td class="border-1 px-6 py-4 text-center">3.00</td>
                            <td class="border-1 px-6 py-4"></td>
                            <td class="border-1 px-6 py-4"></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                LSWDO VMGO
                            </th>
                            <td class="border-1 px-6 py-4 text-center">3.00</td>
                            <td class="border-1 px-6 py-4"></td>
                            <td class="border-1 px-6 py-4"></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                LSWDO Manual of Operations
                            </th>
                            <td class="border-1 px-6 py-4 text-center">3.00</td>
                            <td class="border-1 px-6 py-4"></td>
                            <td class="border-1 px-6 py-4 text-center">0.21</td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                2. Human Resource Management and Development
                            </th>
                            <td class="border-1 px-6 py-4 text-center">2.00</td>
                            <td class="border-1 px-6 py-4"></td>
                            <td class="border-1 px-6 py-4"></td>
                        </tr>
                        <tr class="bg-white border-b border-gray-200">
                            <th scope="row" class="border-1 px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                Registered Social Worker (RSW) as Head of LSWDO
                            </th>
                            <td class="border-1 px-6 py-4 text-center">0.00</td>
                            <td class="border-1 px-6 py-4"></td>
                            <td class="border-1 px-6 py-4"></td>
                        </tr>
                    </tbody>
                </table>
</div>


        
    </div>

@endsection

@section('script')
    @include('admin.periods.script')
@endsection
