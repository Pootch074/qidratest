@extends('layouts.superadmin')
@section('title', 'SUPER ADMIN')
@section('header')
@endsection

@section('content')
    @php $authUser = Auth::user(); @endphp

    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4">Offices</h2>
                <div class="overflow-x-auto flex-1">
                    <div x-show="showSections" x-cloak>
                        <div class="grid grid-cols-3 gap-4 mb-6">

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection
