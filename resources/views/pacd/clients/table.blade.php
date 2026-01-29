@extends('layouts.pacd')
@section('title', 'Clients')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        @include('layouts.inc.pacdsidebar')

        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Client logs</h2>
                <div class="overflow-x-auto flex-1">
                    <table class="min-w-full divide-y divide-gray-200 text-gray-700">
                        <thead class="bg-[#2e3192] text-white sticky top-0 z-10">

                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 overflow-y-auto">

                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </div>
@endsection
