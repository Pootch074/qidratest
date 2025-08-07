@extends('layouts.main')
@section('title', 'Dashboard')

@section('header')
    <!-- fullcalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.18/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {

        var events = @json($events);

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          fixedWeekCount: false,
          events: events
        });
        calendar.render();
      });
    </script>
    <!-- fullcalendar end -->
@endsection


@section('content')

<div class="container mx-auto p-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Total Assessment</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $total }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Pending Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $pending }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Completed Assessments</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $completed }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
        <div class="bg-white shadow-md rounded-lg p-4">
            <span class="block text-gray-700">Extension Request</span>
            <div class="flex justify-between items-center mt-2">
                <p class="text-4xl font-bold">{{ $extension }}</p>
                <div class="p-2 rounded-[15px] bg-[#DB0C16]"><img src="{{ asset('assets/icons/icon-assessment.svg') }}" alt="Search" class="h-7 w-7"></div>
            </div>
        </div>
    </div>

    <div class="flex gap-4 mt-8">
        <div class="w-1/2">
            <div class="bg-white shadow-md rounded-lg p-4 mt-4">

                <div
                    x-data="barChart()"
                    x-init="initChart()"
                    class="max-w-xl mx-auto bg-white p-4 rounded shadow">

                    <h2 class="text-lg font-bold mb-4">LGU Parameter Results</h2>
                    <canvas id="myBarChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="w-1/2">

            <div class="bg-white shadow-md rounded-lg p-4 mt-4">

                <div x-data="groupedBarChart()" x-init="initChart()" class="max-w-6xl mx-auto bg-white p-6 rounded shadow">
                    <h2 class="text-xl font-bold mb-4">Grouped Levels by Location</h2>
                    <canvas id="groupedBarChartCanvas" height="175"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
        function barChart() {
            return {
                chart: null,
                dataJson: {
                    A1: 'Level 1', A2: 'Level 2', A3: 'Level 3', A4: 'Level 3',
                    B1: 'Low', B2: 'Level 3', B3: 'Level 3', B4: 'Level 2',
                    C1: 'Level 2', C2: 'Level 1', C3: 'Level 3', C4: 'Low'
                },
                initChart() {
                    // Add "null" as the bottom level (index 0)
                    const levelOrder = [' ', 'Low', 'Level 1', 'Level 2', 'Level 3'];
                    const labels = Object.keys(this.dataJson);
                    const rawLevels = Object.values(this.dataJson);
                    const numericLevels = rawLevels.map(level => levelOrder.indexOf(level));

                    const ctx = document.getElementById('myBarChart').getContext('2d');

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Level',
                                data: numericLevels,
                                backgroundColor: '#2E3192',
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: 4, // Highest: Level 3 (index 4)
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            const levelNames = [' ', 'Low', 'Level 1', 'Level 2', 'Level 3'];
                                            return levelNames[value] ?? value;
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const val = context.raw;
                                            const levelNames = [' ', 'Low', 'Level 1', 'Level 2', 'Level 3'];
                                            return `Level: ${levelNames[val]}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }

        function groupedBarChart() {
            return {
                chart: null,

                // ✅ Sample data: raw counts
                data: {
                    City:     { 'Level 1': 2,  'Level 2': 4,  'Level 3': 3 },
                    Province: { 'Level 1': 1,  'Level 2': 2,  'Level 3': 2 },
                    Municipality: { 'Level 1': 36, 'Level 2': 5, 'Level 3': 2 }
                },

                initChart() {
                    const ctx = document.getElementById('groupedBarChartCanvas').getContext('2d');
                    const levels = ['Level 1', 'Level 2', 'Level 3'];
                    const locations = Object.keys(this.data); // ['City', 'Province', 'Municipality']
                    const colorMap = {
                        'Level 1': '#2E3192',
                        'Level 2': '#6A75C9',
                        'Level 3': '#C5CAE9'
                    };

                    // Compute total for each location
                    const totals = locations.map(loc =>
                        Object.values(this.data[loc]).reduce((sum, val) => sum + val, 0)
                    );

                    // Build dataset per level (grouped, not stacked)
                    const datasets = levels.map(level => ({
                        label: level,
                        backgroundColor: colorMap[level],
                        data: locations.map((loc, i) => {
                            const value = this.data[loc][level] || 0;
                            const total = totals[i] || 1;
                            return +((value / total) * 100).toFixed(2); // percentage
                        })
                    }));

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: locations,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                x: {
                                    stacked: false,
                                    title: {
                                        display: true,
                                        text: 'Location'
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        callback: value => value + '%'
                                    },
                                    title: {
                                        display: true,
                                        text: 'Percentage'
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const value = context.raw;
                                            return `${context.dataset.label}: ${value}%`;
                                        }
                                    }
                                },
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                }
            }
        }
    </script>
@endpush
