@extends('layouts.admin')

@section('content')
<div class="fms-card">
    <div class="fms-page-header">
        <h1 class="fms-page-title">Monthly Records</h1>
    </div>

    <!-- Month/Year Filter -->
    <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded">
        <form method="GET" action="{{ route('admin.monthly-records.index') }}" class="flex items-center gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <select name="month" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select name="year" class="border border-gray-300 rounded px-3 py-2 text-sm">
                    @foreach(range(2024, 2030) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-6">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded hover:bg-gray-800 text-sm font-medium">
                    View Records
                </button>
            </div>
        </form>
    </div>

    <!-- Records Table -->
    <div class="overflow-x-auto mb-8">
        <h2 class="text-lg font-semibold mb-4 uppercase">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Monthly Trend Chart -->
            <div class="bg-white border border-gray-300 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">Booking Trend (Last 12 Months)</h3>
                <canvas id="monthlyTrendChart" height="200"></canvas>
            </div>

            <!-- Facility Usage Chart -->
            <div class="bg-white border border-gray-300 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">Facility Usage ({{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }})</h3>
                <canvas id="facilityUsageChart" height="200"></canvas>
            </div>
        </div>

        @if($records->count() > 0)
            <table class="w-full border-2 border-black text-xs">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="border border-black px-2 py-2 text-left">Date Requested</th>
                        <th class="border border-black px-2 py-2 text-left">Date of Activity</th>
                        <th class="border border-black px-2 py-2 text-left">Time</th>
                        <th class="border border-black px-2 py-2 text-left">Purpose</th>
                        <th class="border border-black px-2 py-2 text-left">Requester</th>
                        <th class="border border-black px-2 py-2 text-left">Contact No.</th>
                        <th class="border border-black px-2 py-2 text-left">Venue</th>
                        <th colspan="7" class="border border-black px-2 py-2 text-center bg-gray-800">Facilities to be Used (Quantity)</th>
                        <th class="border border-black px-2 py-2 text-right">Rental Fee</th>
                    </tr>
                    <tr>
                        <th colspan="7" class="border border-black"></th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Chairs</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Tables</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Fans</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Rostrum</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Flags</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">Sound</th>
                        <th class="border border-black px-2 py-1 text-center text-xs">LED</th>
                        <th class="border border-black"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($records as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-black px-2 py-2">{{ $record['date_requested'] }}</td>
                            <td class="border border-black px-2 py-2">{{ $record['date_of_activity'] }}</td>
                            <td class="border border-black px-2 py-2">{{ $record['time'] }}</td>
                            <td class="border border-black px-2 py-2">{{ Str::limit($record['purpose'], 40) }}</td>
                            <td class="border border-black px-2 py-2">{{ $record['requester'] }}</td>
                            <td class="border border-black px-2 py-2">{{ $record['contact_no'] }}</td>
                            <td class="border border-black px-2 py-2 font-semibold">{{ $record['venue'] }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['chairs'] > 0 ? $record['equipment']['chairs'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['tables'] > 0 ? $record['equipment']['tables'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['fans'] > 0 ? $record['equipment']['fans'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['rostrum'] > 0 ? $record['equipment']['rostrum'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['flags'] > 0 ? $record['equipment']['flags'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['sound'] > 0 ? $record['equipment']['sound'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-center">{{ $record['equipment']['led'] > 0 ? $record['equipment']['led'] : '-' }}</td>
                            <td class="border border-black px-2 py-2 text-right font-semibold">
                                @if($record['rental_fee'] > 0)
                                    ₱{{ number_format($record['rental_fee'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-bold">
                    <tr>
                        <td colspan="14" class="border border-black px-2 py-2 text-right">TOTAL RENTAL FEES:</td>
                        <td class="border border-black px-2 py-2 text-right text-lg">₱{{ number_format($totalRentalFees, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="text-center py-12 text-gray-500">
                <p>No bookings found for {{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</p>
            </div>
        @endif
    </div>

    <!-- Venue Usage Summary -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-4 uppercase">Summary of Venues/Facilities Utilized as of {{ date('F, Y', mktime(0, 0, 0, $month, 1, $year)) }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @php
                $allVenues = [
                    'Balay ni jUAn' => 0,
                    'bUsAlian hall' => 0,
                    'e-hub' => 0,
                    'GrandStand/Track Oval' => 0,
                    'ICT AVR' => 0,
                    'CTE AVR' => 0,
                    'CEA AVR' => 0,
                    'New AVR' => 0,
                    'New Female Dorm' => 0,
                    'Paghiusa hall' => 0,
                    'Tiripunan hall' => 0,
                    'UA Hostel' => 0,
                    'VIP Canteen' => 0,
                    'Old/New Library' => 0,
                    'Science Bldg.' => 0,
                ];

                // Merge actual usage with all venues
                foreach($venueSummary as $venue => $count) {
                    $allVenues[$venue] = $count;
                }
            @endphp

            @foreach($allVenues as $venue => $count)
                <div class="border border-gray-300 rounded p-3 bg-white">
                    <p class="text-sm font-semibold text-gray-700">{{ $venue }}</p>
                    <p class="text-2xl font-bold {{ $count > 0 ? 'text-blue-600' : 'text-gray-400' }}">{{ $count }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Export/Print Actions -->
    <div class="flex justify-end gap-3 pt-4 border-t">
        <button onclick="window.print()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm font-medium">
            Print Records
        </button>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm font-medium">
            Back to Dashboard
        </a>
    </div>
</div>

<style>
@media print {
    .fms-sidebar,
    button,
    form,
    .border-t {
        display: none !important;
    }

    .fms-card {
        box-shadow: none !important;
        border: none !important;
    }

    table {
        font-size: 10px !important;
    }
}
</style>
@endsection

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Monthly Trend Chart
const monthlyTrendData = {
    labels: @json(array_column($monthlyTrend, 'month')),
    datasets: [{
        label: 'Number of Bookings',
        data: @json(array_column($monthlyTrend, 'count')),
        borderColor: '#000000',
        backgroundColor: 'rgba(0, 0, 0, 0.1)',
        tension: 0.3,
        fill: true
    }]
};

new Chart(document.getElementById('monthlyTrendChart'), {
    type: 'line',
    data: monthlyTrendData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Facility Usage Chart
const facilityLabels = @json($venueSummary->keys()->toArray());
const facilityCounts = @json($venueSummary->values()->toArray());

const colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6', '#f97316', '#6366f1', '#84cc16', '#06b6d4', '#a855f7', '#f43f5e', '#22c55e', '#eab308'];

new Chart(document.getElementById('facilityUsageChart'), {
    type: 'bar',
    data: {
        labels: facilityLabels,
        datasets: [{
            label: 'Number of Bookings',
            data: facilityCounts,
            backgroundColor: colors.slice(0, facilityLabels.length),
            borderColor: '#000000',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            },
            x: {
                ticks: {
                    autoSkip: false,
                    maxRotation: 45,
                    minRotation: 45,
                    font: {
                        size: 10
                    }
                }
            }
        }
    }
});
</script>
