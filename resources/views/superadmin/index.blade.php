@extends('layouts.superadmin')
@section('title', 'SUPER ADMIN')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Offices</h2>
                <div class="overflow-x-auto flex-1">
                    <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tl-lg">
                                    Office Name</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Client Type</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Step</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider">Section</th>
                                <th class="px-6 py-3 text-left text-sm font-medium uppercase tracking-wider rounded-tr-lg">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">
                            <tr class="odd:bg-white even:bg-gray-200 hover:bg-indigo-50 transition duration-200">
                                <td class="px-6 py-4 font-semibold">

                                </td>
                                <td class="px-6 py-4">

                                </td>

                                <td class="px-6 py-4">{{ $transaction->step->step_number ?? '—' }}</td>
                                <td class="px-6 py-4">{{ $transaction->section->section_name ?? '—' }}</td>

                                <td class="px-6 py-4">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
