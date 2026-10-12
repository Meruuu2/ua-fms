@extends('layouts.guest')

@section('content')
    <!-- Dashboard Topbar with Notifications and Logout -->
    <div class="flex justify-between items-center border-b border-black pb-3 mb-6">
        <h1 class="text-2xl font-semibold text-black">Guest Dashboard</h1>
        <div class="flex items-center gap-4">
            <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-black hover:underline flex items-center gap-2">
                Notifications
                @if(isset($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-black rounded-full">{{ $unreadNotificationsCount }}</span>
                @endif
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0 inline">
                @csrf
                <button type="submit" class="text-sm font-medium text-black hover:underline">Logout</button>
            </form>
        </div>
    </div>

    <div class="fms-card">
        <p class="mb-6 text-sm text-neutral-600">
            Welcome to the Guest Portal. Submit facility requests with payment and track your reservations.
        </p>

        <!-- Statistics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Facilities Requests Chart (3D Pie Chart) -->
            <div class="bg-neutral-50 border border-neutral-200 rounded-lg p-6">
                <h3 class="text-base font-semibold mb-4">My Facilities Requests</h3>
                <div class="relative" style="height: 300px;">
                    <div id="facilitiesChart3D"></div>
                </div>
                <div class="mt-4 text-sm text-neutral-600">
                    <p>Total Requests: <strong>{{ $facilitiesStats['total'] }}</strong></p>
                </div>
            </div>
        </div>

        <!-- UPCOMING EVENTS Section -->
        <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-neutral-500">Upcoming Events</h2>
        <div class="mb-8 bg-neutral-50 border border-neutral-200 rounded-lg p-6">
            @if($upcomingEvents->count() > 0)
                <div class="space-y-4">
                    @foreach($upcomingEvents as $event)
                        <div class="flex justify-between items-start border-b border-neutral-200 pb-4 last:border-0 last:pb-0">
                            <div>
                                <p class="font-semibold text-black">{{ $event->start_time->format('M d, Y') }}</p>
                                <p class="text-sm text-neutral-600">{{ $event->start_time->format('h:i A') }} - {{ $event->end_time->format('h:i A') }}</p>
                                <p class="font-medium text-black mt-2">{{ strtoupper($event->facilities->first()->name ?? 'N/A') }}</p>
                                <p class="text-sm text-neutral-600">{{ $event->purpose }}</p>
                            </div>
                            @if($event->requester_id == auth()->id())
                                <span class="text-xs bg-black text-white px-2 py-1 rounded">Your Booking</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('guest.bookings.calendar') }}" class="mt-4 inline-block text-sm font-medium text-black hover:underline">
                    View Full Calendar →
                </a>
            @else
                <p class="text-sm text-neutral-600">No upcoming events scheduled.</p>
            @endif
        </div>

        <h2 class="mb-3 text-sm font-semibold uppercase tracking-widest text-neutral-500">Quick access</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('guest.rates') }}" class="fms-stat-card bg-blue-50 border-blue-200">
                <h3>Venue Rates</h3>
                <p>View official pricing and facilities packages</p>
            </a>
            <a href="{{ route('guest.requests.facilities.create') }}" class="fms-stat-card">
                <h3>New Request</h3>
                <p>Submit a facility booking request with payment</p>
            </a>
            <a href="{{ route('guest.requests.facilities.index') }}" class="fms-stat-card">
                <h3>My Requests</h3>
                <p>View and track your facility requests</p>
            </a>
            <a href="{{ route('guest.bookings.calendar') }}" class="fms-stat-card">
                <h3>Calendar</h3>
                <p>View all facility bookings and availability</p>
            </a>
            <a href="{{ route('guest.bookings.index') }}" class="fms-stat-card">
                <h3>My Bookings</h3>
                <p>View your confirmed reservations</p>
            </a>
        </div>
    </div>

    <script src="https://cdn.plot.ly/plotly-2.27.0.min.js"></script>
    <script>
    // Facilities Requests 3D Pie Chart using Plotly
    const facilitiesData = [{
        values: [
            {{ $facilitiesStats['pending'] }},
            {{ $facilitiesStats['pending_payment'] }},
            {{ $facilitiesStats['approved'] }},
            {{ $facilitiesStats['reserved'] }},
            {{ $facilitiesStats['disapproved'] }}
        ],
        labels: ['Pending', 'Pending Payment', 'Approved', 'Reserved', 'Disapproved'],
        type: 'pie',
        marker: {
            colors: [
                '#FCD34D', // Yellow - Pending
                '#FB923C', // Orange - Pending Payment
                '#34D399', // Green - Approved
                '#60A5FA', // Blue - Reserved
                '#F87171'  // Red - Disapproved
            ],
            line: {
                color: '#fff',
                width: 2
            }
        },
        hole: 0,
        pull: [0, 0.05, 0, 0, 0],
        textinfo: 'label+percent',
        textposition: 'outside',
        automargin: true
    }];

    const facilitiesLayout = {
        height: 300,
        margin: { t: 20, b: 20, l: 20, r: 20 },
        paper_bgcolor: 'rgba(0,0,0,0)',
        plot_bgcolor: 'rgba(0,0,0,0)',
        showlegend: true,
        legend: {
            orientation: 'h',
            y: -0.2,
            x: 0.5,
            xanchor: 'center'
        }
    };

    const facilitiesConfig = {
        responsive: true,
        displayModeBar: false
    };

    Plotly.newPlot('facilitiesChart3D', facilitiesData, facilitiesLayout, facilitiesConfig);
    </script>
@endsection
