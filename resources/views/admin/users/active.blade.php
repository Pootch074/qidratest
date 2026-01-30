@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <div class="p-4 sm:ml-64">
            <div class="bg-white rounded-lg p-4 shadow-lg h-[84vh] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-700">Active Users</h2>
                </div>
                @livewire('admin.active-users-table')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script>
        window.appBaseUrl = "{{ url('') }}";
        window.userColumnsCount = {{ count($userColumns) + 3 }};
    </script> --}}
    @vite('resources/js/adminUsers.js')
@endsection
