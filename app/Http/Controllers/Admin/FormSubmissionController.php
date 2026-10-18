<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\Facility;
use App\Models\User;

use App\Services\BookingService;
use App\Services\NotificationService;
use Illuminate\Http\Request;


class FormSubmissionController extends Controller
{ 
    public function __construct(
        protected BookingService $bookingService,
        protected NotificationService $notifications
    ) {}

    /**
     * List facilities utilization form submissions with filtering and search.
     */
    public function index(Request $request)
    {
        $query = FormSubmission::with('requester')
            ->where('type', 'facilities_utilization');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by facility
        if ($request->filled('facility_id')) {
            $facilityId = $request->input('facility_id');
            $query->whereJsonContains('payload->facility_id', (int) $facilityId);
        }

        // Filter by month (date_activity is stored as YYYY-MM-DD in payload)
        if ($request->filled('month')) {
            $month = $request->input('month'); // format: YYYY-MM
            $query->whereRaw('JSON_EXTRACT(payload, \'$.date_activity\') LIKE ?', [$month . '%']);
        }

        // Search by control number
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereRaw('JSON_EXTRACT(payload, \'$.control_no\') LIKE ?', ['%' . $search . '%']);
        }

        // Sort: control number A-Z (pending requests first without control no), then by date
        // Requests that still need a decision come first (pending, then payment to verify), then the rest; newest first in each group
        $submissions = $query->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'pending_payment' THEN 1 WHEN 'approved' THEN 2 WHEN 'reserved' THEN 3 WHEN 'disapproved' THEN 4 WHEN 'cancelled' THEN 5 ELSE 6 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        // Get available facilities for filter dropdown
        $facilities = Facility::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.forms.facilities_index', compact('submissions', 'facilities'));
    }

    /**
     * Show a single submission.
     */
    public function show(FormSubmission $submission)
    {
        if ($submission->type !== 'facilities_utilization') {
            abort(404);
        }

        $payload = $submission->payload ?? [];
        $facility = null;

        if (!empty($payload['facility_id'])) {
            $facility = Facility::find($payload['facility_id']);
        }

        return view('admin.forms.facilities_show', compact('submission', 'payload', 'facility'));
    }

    /**
     * Approve a submission (GSU side).
     */
    public function approve(FormSubmission $submission)
    {
        if ($submission->type !== 'facilities_utilization') {
            abort(404);
        }

        // Check if payment is required and uploaded but not verified
        if ($submission->payment_status === 'payment_uploaded') {
            return back()->withErrors(['status' => 'Please verify the payment attachment before approving this request.']);
        }

        $submission->status = 'approved';
        $payload = $submission->payload ?? [];

        // Generate control number if it doesn't exist.
        if (empty($payload['control_no'])) {
            $timestamp = now()->format('YmdHis');
            $randomDigit = rand(0, 9);
            $payload['control_no'] = "BKG-{$timestamp}-{$randomDigit}";
        }

        $payload['approved_datetime'] = now()->format('M d, Y h:i A');
        $submission->payload = $payload;
        $submission->save();

        if ($submission->requester) {
            $this->notifications->notifyFormApproved($submission->requester_id, $submission->id);
        }

        // Automatically create booking/reservation
        $result = $this->bookingService->createFromSubmission($submission);

        if (is_string($result)) {
            // If booking creation failed, show error but keep the approval
            return redirect()->route('admin.forms.facilities.index')
                ->with('error', 'Request approved, but booking creation failed: ' . $result);
        }

        $booking = $result;

        return redirect()->route('admin.forms.facilities.index')
            ->with('status', "Request approved and reservation created (Code: {$booking->booking_code}). It will now appear on the calendar.");
    }

    /**
     * Disapprove a submission.
     */
    public function disapprove(FormSubmission $submission)
    {
        if ($submission->type !== 'facilities_utilization') {
            abort(404);
        }

        $submission->status = 'disapproved';
        $submission->save();

        if ($submission->requester) {
            $this->notifications->notifyFormDisapproved($submission->requester_id, $submission->id);
        }


        return redirect()->route('admin.forms.facilities.index')
            ->with('status', 'Request disapproved.');
    }

    /**
     * Convert an approved facilities form into a Reservation.
     *
     * This enforces:
     * - Only approved submissions are allowed.
     * - Facility must not be unavailable/maintenance.
     * - No overlapping reserved bookings for the same facility & time.
     */
    public function setReservation(FormSubmission $submission)
    {
        if ($submission->type !== 'facilities_utilization') {
            abort(404);
        }

        if ($submission->status !== 'approved') {
            return back()->withErrors(['status' => 'Only approved requests can be converted to reservations.']);
        }

        $result = $this->bookingService->createFromSubmission($submission);

        if (is_string($result)) {
            return back()->withErrors(['status' => $result]);
        }

        $booking = $result;

        return redirect()->route('admin.forms.facilities.index')
            ->with('status', "Reservation created (Code: {$booking->booking_code}) and request marked as reserved.");
    }

    /**
     * Verify payment attachment for a submission.
     */
    public function verifyPayment(FormSubmission $submission)
    {
        if ($submission->type !== 'facilities_utilization') {
            abort(404);
        }

        if ($submission->payment_status !== 'payment_uploaded') {
            return back()->withErrors(['status' => 'No payment to verify.']);
        }

        $submission->markPaymentVerified();

        return back()->with('status', 'Payment verified successfully.');
    }

}