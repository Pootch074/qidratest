@extends('layouts.admin')
@section('title', 'Admin')
@section('header')
@endsection

@section('content')
    <div class="w-full p-4 bg-gray-200">
        <h1>Admin</h1>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/adminUsers.js')
@endsection
