@extends('layouts.main')
@section('title', 'Deadlines')

@section('content')

<div class="container">
    <div class="col card border-0 m-3 p-5">
        <div class="row g-0 card deadlines-table">
            <div class="row g-0 mb-2 p-4">
                <div class="col d-flex align-items-center table-search">
                    <div class="input-group">
                        <span class="input-group-text">
                            <img src="{{ Vite::asset('resources/assets/icons/icon-search.png') }}" alt="Search">
                        </span>
                        <input class="form-control w-50" placeholder="Search" />
                    </div>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <th>LGU Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </thead>
                <tbody>
                    @for ($i = 0; $i < 20; $i++)
                    <tr>
                        <td>LGU 1</td>
                        <td>March 1, 2025</td>
                        <td>June 28, 2025</td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
