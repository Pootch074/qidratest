@extends('layouts.main')
@section('title', 'Deadlines')

@section('content')

<div class="container">
    <div class="col card border-0 m-3 p-5">
        <div class="row g-0 card deadlines-table">

            @livewire('rmt-deadlines')
            
        </div>
    </div>
</div>

@endsection
