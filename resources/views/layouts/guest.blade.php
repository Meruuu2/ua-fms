@extends('layouts.app')

{{-- Removed max-w-[1440px] to MAXIMIZE the UI width across the entire screen --}}
@section('main-class', 'w-full flex-1 px-0 py-0')

@section('content')
<div class="fms-shell">
    @include('layouts.partials.sidebar', [
        'portalTitle' => 'Guest Access',
        'portalSubtitle' => optional(auth()->user())->organization_name ?? 'Guest User',
        'sections' => [
            [
                'heading' => 'Overview',
                'links' => [
                    ['label' => 'Dashboard', 'route' => 'guest.dashboard'],
                    ['label' => 'Reservation calendar', 'route' => 'guest.bookings.calendar'],
                    ['label' => 'My Bookings', 'route' => 'guest.bookings.index', 'routes' => 'guest.bookings.*'],
                ],
            ],
            [
                'heading' => 'Requests',
                'links' => [
                    ['label' => 'My requests', 'route' => 'guest.requests.facilities.index'],
                    ['label' => 'New request', 'route' => 'guest.requests.facilities.create'],
                ],
            ],
        ],
        'footer' => '<span class="text-black font-medium">Signed in as</span><br>' . e(optional(auth()->user())->name),
    ])

    <section class="fms-main">
        {{-- Dashboard Topbar --}}
        <div class="flex justify-between items-center border-b border-black pb-3 mb-4 px-0 py-4">
            <h1 class="text-xl font-semibold text-black">Guest Portal</h1>
            <div class="flex items-center gap-4">
                <a href="{{ route('notifications.index') }}" class="text-sm font-medium text-black hover:underline flex items-center gap-2">
                    Notifications
                    @if($unreadNotificationsCount ?? 0 > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-black rounded-full">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0 inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-black hover:underline">Logout</button>
                </form>
            </div>
        </div>

        <div class="fms-content-wrapper">
            @yield('content')
        </div>
    </section>
</div>

@endsection
