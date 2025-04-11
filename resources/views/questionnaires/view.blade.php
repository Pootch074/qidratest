@extends('layouts.main')
@section('title', 'Questionnaires')

@section('content')


    <h2 class="mb-5">
        <span class="bg-[#2E3192] text-l inline-flex items-center gap-2 border px-4 py-2 font-medium text-white rounded-full">LGU Name</span>
        <span class="font-medium text-xl inline-block align-middle ml-2 text-[#1B1D21]">Administration and Organization</span>
    </h2>

    <div class="flex mb-4">
        <div class="w-4/6 bg-white p-[30px] rounded-lg m-3 shadow-md">
            @include('questionnaires.inc.questions')
        </div>
        <div class="w-2/6 bg-white p-[30px] rounded-lg m-3 shadow-md">
            @include('questionnaires.inc.nav')
        </div>
    </div>

@endsection

@section('script')
    @include('questionnaires.script')
@endsection
