@extends('layouts.app')

{{-- Removed max-w-[1440px] to MAXIMIZE the UI width across the entire screen --}}
@section('main-class', 'w-full flex-1 px-0 py-0')

@section('content')
<div class="fms-shell">
    @include('layouts.partials.sidebar', [
        'portalTitle' => 'GSU Admin',
        'portalSubtitle' => optional(auth()->user())->name,
        'sections' => [
            [
                'heading' => 'Overview',
                'links' => [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
                    ['label' => 'Monthly records', 'route' => 'admin.monthly-records.index'],
                ],
            ],
            [
                'heading' => 'Facilities',
                'links' => [
                    ['label' => 'All facilities', 'route' => 'admin.facilities.index'],
                    ['label' => 'Add facility', 'route' => 'admin.facilities.create'],
                ],
            ],
            [
                'heading' => 'Requests',
                'links' => [
                    [
                        'label'  => ' Approve Requests',
                        'route'  => 'admin.forms.facilities.index',
                        'routes' => 'admin.forms.facilities.*',
                    ],
                    [
                        'label'  => 'Repair & maintenance',
                        'route'  => 'admin.maintenance-tickets.index',
                        'routes' => 'admin.maintenance-tickets.*',
                    ],
                    [
                        'label'  => 'Reservations',
                        'route'  => 'admin.bookings.index',
                        'routes' => ['admin.bookings.index'],
                    ],
                    [
                        'label'  => 'Create Direct Reservation',
                        'route'  => 'admin.bookings.create-direct',
                        'routes' => ['admin.bookings.create-direct'],
                    ],
                    [
                        'label' => 'Reservation calendar',
                        'route' => 'admin.calendar',
                    ],
                    [
                        'label' => 'Monthly overview',
                        'route' => 'admin.overview',
                    ],
                ],
            ],
            [
                'heading' => 'Administration',
                'links' => [
                    ['label' => 'Users', 'route' => 'admin.users.index'],
                    ['label' => 'Create user', 'route' => 'admin.users.create'],
                ],
            ],
        ],
    ])

    <section class="fms-main">
        {{-- Dashboard Topbar --}}
        <div class="flex justify-between items-center border-b border-black pb-3 mb-4 px-0 py-4">
            <h1 class="text-xl font-semibold text-black">GSU Admin Portal</h1>
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
            @yield('admin-content')
        </div>
    </section>
</div>

@endsection

