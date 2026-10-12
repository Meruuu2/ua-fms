<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlyRecordsController extends Controller
{
    public function index(Request $request)
    {
        // Get month and year from request or default to current month
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Create date range for the selected month
        $startDate = Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        // Get all bookings (reserved status) for the month
        $bookings = Booking::with(['facilities', 'requester'])
            ->whereBetween('start_time', [$startDate, $endDate])
            ->where('status', 'reserved')
            ->orderBy('start_time', 'asc')
            ->get();

        // Get all form submissions for this month to try to match them
        $requesterIds = $bookings->pluck('requester_id')->unique();
        $formSubmissions = FormSubmission::whereIn('requester_id', $requesterIds)
            ->where('type', 'facilities_utilization')
            ->get()
            ->keyBy('requester_id');

        // Format bookings for display
        $records = $bookings->map(function ($booking) use ($formSubmissions) {
            $facility = $booking->facilities->first();
            $formSubmission = $formSubmissions->get($booking->requester_id);
            $payload = $formSubmission ? $formSubmission->payload : [];

            return [
                'date_requested' => $booking->requested_at ? $booking->requested_at->format('F d, Y') : ($booking->created_at ? $booking->created_at->format('F d, Y') : '-'),
                'date_of_activity' => $booking->start_time->format('F d, Y'),
                'time' => $booking->start_time->format('g:ia') . '-' . $booking->end_time->format('g:ia'),
                'purpose' => $booking->purpose ?? '-',
                'requester' => $booking->requester->name ?? '-',
                'contact_no' => $booking->requester->contact_number ?? '-',
                'venue' => $facility ? $facility->name : '-',
                'equipment' => [
                    'chairs' => $payload['qty_monobloc'] ?? 0,
                    'tables' => $payload['qty_table'] ?? 0,
                    'fans' => $payload['qty_fan'] ?? 0,
                    'rostrum' => $payload['qty_rostrum'] ?? 0,
                    'flags' => $payload['qty_flag'] ?? 0,
                    'sound' => $payload['qty_sound'] ?? 0,
                    'led' => $payload['qty_led'] ?? 0,
                ],
                'rental_fee' => $facility ? ($facility->rental_fee ?? 0) : 0,
            ];
        });

        // Calculate venue usage summary
        $venueSummary = $bookings->groupBy(function ($booking) {
            $facility = $booking->facilities->first();
            return $facility ? $facility->name : 'Unknown';
        })->map(function ($group) {
            return $group->count();
        })->sortKeys();

        // Calculate total rental fees
        $totalRentalFees = $records->sum('rental_fee');

        // Get monthly trend data (last 12 months)
        $monthlyTrend = [];
        for ($i = 11; $i >= 0; $i--) {
            $trendMonth = now()->subMonths($i);
            $count = Booking::whereBetween('start_time', [
                $trendMonth->copy()->startOfMonth(),
                $trendMonth->copy()->endOfMonth()
            ])
            ->where('status', 'reserved')
            ->count();

            $monthlyTrend[] = [
                'month' => $trendMonth->format('M Y'),
                'count' => $count
            ];
        }

        return view('admin.monthly-records.index', compact(
            'records',
            'venueSummary',
            'totalRentalFees',
            'month',
            'year',
            'monthlyTrend'
        ));
    }
}
