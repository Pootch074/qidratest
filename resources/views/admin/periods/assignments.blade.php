@extends('layouts.main')
@section('title', 'RMT Management')

@section('content')

    <div class="container mx-auto p-4 bg-white rounded-xl">
        @livewire('admin-assignments')
    </div>

@endsection

@section('script')
{{--    @include('admin.periods.ascript')--}}
@endsection
