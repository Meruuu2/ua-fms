<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\FormSubmissionController as AdminFormSubmissionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\MonthlyRecordsController as AdminMonthlyRecordsController;
use App\Http\Controllers\College\DashboardController as CollegeDashboardController;
use App\Http\Controllers\College\FacilityController as CollegeFacilityController;
use App\Http\Controllers\College\FormController as CollegeFormController;
use App\Http\Controllers\Org\DashboardController as OrgDashboardController;
use App\Http\Controllers\Org\FormController as OrgFormController;
use App\Http\Controllers\Guest\DashboardController as GuestDashboardController;
use App\Http\Controllers\Guest\FormController as GuestFormController;
use App\Http\Controllers\Guest\BookingController as GuestBookingController;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GsuFormController;
use App\Http\Controllers\PublicCalendarController;

// Authentication routes (simple web login)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::match(['GET','POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Notifications (for all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])
        ->name('notifications.index');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])
        ->name('notifications.read');
});

// home route for viewer/general
Route::get('/', [\App\Http\Controllers\PublicCalendarController::class, 'index'])
    ->name('home');





// Simple health check (for debugging)
Route::get('/healthz', function () {
    return response()->json([
        'status' => 'ok',
        'time'   => now()->toDateTimeString(),
    ]);
});

// Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/admin/maintenance-tickets', [\App\Http\Controllers\Admin\MaintenanceTicketController::class, 'index'])
        ->name('admin.maintenance-tickets.index');
    Route::get('/admin/maintenance-tickets/{ticket}', [\App\Http\Controllers\Admin\MaintenanceTicketController::class, 'show'])
        ->name('admin.maintenance-tickets.show');
    Route::put('/admin/maintenance-tickets/{ticket}', [\App\Http\Controllers\Admin\MaintenanceTicketController::class, 'update'])
        ->name('admin.maintenance-tickets.update');

    // Facilities
    Route::get('/admin/facilities', [AdminFacilityController::class, 'index'])->name('admin.facilities.index');
    Route::get('/admin/facilities/create', [AdminFacilityController::class, 'create'])->name('admin.facilities.create');
    Route::post('/admin/facilities', [AdminFacilityController::class, 'store'])->name('admin.facilities.store');
    Route::get('/admin/facilities/{facility}/edit', [AdminFacilityController::class, 'edit'])->name('admin.facilities.edit');
    Route::put('/admin/facilities/{facility}', [AdminFacilityController::class, 'update'])->name('admin.facilities.update');
    Route::delete('/admin/facilities/{facility}', [AdminFacilityController::class, 'destroy'])->name('admin.facilities.destroy');

    // GSU Forms (accessible by admin/GSU staff)
    Route::get('/forms/facilities', [GsuFormController::class, 'showFacilities'])->name('forms.facilities.show');
    Route::post('/forms/facilities/download', [GsuFormController::class, 'downloadFacilities'])->name('forms.facilities.download');
    Route::get('/forms/repair', [GsuFormController::class, 'showRepair'])->name('forms.repair.show');
    Route::post('/forms/repair/download', [GsuFormController::class, 'downloadRepair'])->name('forms.repair.download');

    // Facilities form submissions (GSU review)
    Route::get('/admin/forms/facilities', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'index'])
        ->name('admin.forms.facilities.index');
    Route::get('/admin/forms/facilities/{submission}', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'show'])
        ->name('admin.forms.facilities.show');
    Route::post('/admin/forms/facilities/{submission}/approve', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'approve'])
        ->name('admin.forms.facilities.approve');
    Route::post('/admin/forms/facilities/{submission}/disapprove', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'disapprove'])
        ->name('admin.forms.facilities.disapprove');
    Route::post('/admin/forms/facilities/{submission}/verify-payment', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'verifyPayment'])
        ->name('admin.forms.facilities.verify-payment');
    Route::post('/admin/forms/facilities/{submission}/set-reservation', [\App\Http\Controllers\Admin\FormSubmissionController::class, 'setReservation'])
        ->name('admin.forms.facilities.set-reservation');

    // Generate PDF for approved facilities requests
    Route::get(
        '/admin/forms/facilities/{submission}/pdf',
        [\App\Http\Controllers\Admin\FacilitiesFormPdfController::class, 'generate']
    )->name('admin.forms.facilities.pdf');

    // User management
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('admin.users.store');

    // Monthly Records
    Route::get('/admin/monthly-records', [AdminMonthlyRecordsController::class, 'index'])->name('admin.monthly-records.index');

    // Direct booking (GSU high-priority)
    Route::get('/admin/bookings/create-direct', [\App\Http\Controllers\Admin\BookingController::class, 'createDirect'])
        ->name('admin.bookings.create-direct');
    Route::post('/admin/bookings/store-direct', [\App\Http\Controllers\Admin\BookingController::class, 'storeDirect'])
        ->name('admin.bookings.store-direct');


    // Booking management (GSU can override & reschedule)
    Route::get('/admin/bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])
        ->name('admin.bookings.index');
    Route::get('/admin/bookings/{booking}/edit', [\App\Http\Controllers\Admin\BookingController::class, 'edit'])
        ->name('admin.bookings.edit');
    Route::post('/admin/bookings/{booking}', [\App\Http\Controllers\Admin\BookingController::class, 'update'])
        ->name('admin.bookings.update');
    Route::post('/admin/bookings/{booking}/cancel', [\App\Http\Controllers\Admin\BookingController::class, 'cancel'])
        ->name('admin.bookings.cancel');
    Route::get('/admin/calendar', [\App\Http\Controllers\Admin\BookingController::class, 'calendar'])
        ->name('admin.calendar');
    Route::get('/admin/overview', [\App\Http\Controllers\Admin\BookingController::class, 'overview'])
        ->name('admin.overview');
});

// College Staff
Route::middleware(['auth', 'role:college_staff'])->group(function () {
    Route::get('/college/dashboard', [CollegeDashboardController::class, 'index'])->name('college.dashboard');

    Route::get('/college/maintenance-requests', [\App\Http\Controllers\College\MaintenanceTicketController::class, 'index'])
        ->name('college.maintenance-tickets.index');
    Route::get('/college/maintenance-requests/create', [\App\Http\Controllers\College\MaintenanceTicketController::class, 'create'])
        ->name('college.maintenance-tickets.create');
    Route::post('/college/maintenance-requests', [\App\Http\Controllers\College\MaintenanceTicketController::class, 'store'])
        ->name('college.maintenance-tickets.store');
    Route::get('/college/maintenance-requests/{ticket}', [\App\Http\Controllers\College\MaintenanceTicketController::class, 'show'])
        ->name('college.maintenance-tickets.show');

    // Facilities
    Route::get('/college/facilities', [CollegeFacilityController::class, 'index'])->name('college.facilities.index');
    Route::get('/college/facilities/create', [CollegeFacilityController::class, 'create'])->name('college.facilities.create');
    Route::post('/college/facilities', [CollegeFacilityController::class, 'store'])->name('college.facilities.store');
    Route::get('/college/facilities/{facility}/edit', [CollegeFacilityController::class, 'edit'])->name('college.facilities.edit');
    Route::put('/college/facilities/{facility}', [CollegeFacilityController::class, 'update'])->name('college.facilities.update');
    Route::delete('/college/facilities/{facility}', [CollegeFacilityController::class, 'destroy'])->name('college.facilities.destroy');

    // Bookings list (now routed through controller)
    Route::get(
        '/college/bookings',
        [\App\Http\Controllers\College\BookingController::class, 'index']
    )->name('college.bookings.index');


    // College booking calendar (read-only)
    Route::get('/college/calendar', [\App\Http\Controllers\College\BookingController::class, 'calendar'])
        ->name('college.calendar');

    // Facilities Utilization Form (College → GSU)
    Route::get('/college/requests/facilities', [\App\Http\Controllers\College\FormController::class, 'createFacilities'])
        ->name('college.requests.facilities.create');
    Route::post('/college/requests/facilities', [\App\Http\Controllers\College\FormController::class, 'storeFacilities'])
        ->name('college.requests.facilities.store');

    // My Facilities Requests (College)
    Route::get('/college/requests', [\App\Http\Controllers\College\FormController::class, 'indexFacilities'])
        ->name('college.requests.index');

    Route::get(
        '/college/requests/facilities/{submission}',
        [\App\Http\Controllers\College\FormController::class, 'showFacilities']
    )->name('college.requests.facilities.show');

    Route::post(
        '/college/requests/facilities/{submission}/cancel',
        [\App\Http\Controllers\College\FormController::class, 'cancelFacilities']
    )->name('college.requests.facilities.cancel');
});

// Org Staff
Route::middleware(['auth', 'role:org_staff'])->group(function () {
    Route::get('/org/dashboard', [OrgDashboardController::class, 'index'])->name('org.dashboard');

    // Bookings
    Route::get('/org/bookings', [\App\Http\Controllers\Org\BookingController::class, 'index'])
        ->name('org.bookings.index');
    Route::get('/org/calendar', [\App\Http\Controllers\Org\BookingController::class, 'calendar'])
        ->name('org.bookings.calendar');

    // Org Maintenance Requests
    Route::get('/org/maintenance-requests', [\App\Http\Controllers\Org\MaintenanceTicketController::class, 'index'])
        ->name('org.maintenance-tickets.index');
    Route::get('/org/maintenance-requests/create', [\App\Http\Controllers\Org\MaintenanceTicketController::class, 'create'])
        ->name('org.maintenance-tickets.create');
    Route::post('/org/maintenance-requests', [\App\Http\Controllers\Org\MaintenanceTicketController::class, 'store'])
        ->name('org.maintenance-tickets.store');
    Route::get('/org/maintenance-requests/{ticket}', [\App\Http\Controllers\Org\MaintenanceTicketController::class, 'show'])
        ->name('org.maintenance-tickets.show');

    // Facilities Utilization Form (Org → GSU)
    Route::get('/org/requests/facilities', [OrgFormController::class, 'createFacilities'])
        ->name('org.requests.facilities.create');
    Route::post('/org/requests/facilities', [OrgFormController::class, 'storeFacilities'])
        ->name('org.requests.facilities.store');
    Route::get('/org/requests/facilities/index', [OrgFormController::class, 'indexFacilities'])
        ->name('org.requests.facilities.index');
    Route::get('/org/requests/facilities/{submission}', [OrgFormController::class, 'showFacilities'])
        ->name('org.requests.facilities.show');

    Route::post(
        '/org/requests/facilities/{submission}/cancel',
        [OrgFormController::class, 'cancelFacilities']
    )->name('org.requests.facilities.cancel');
});

// Guest (External Users)
Route::middleware(['auth', 'role:guest'])->group(function () {
    Route::get('/guest/dashboard', [GuestDashboardController::class, 'index'])->name('guest.dashboard');

    // Venue Rates
    Route::get('/guest/rates', function () {
        return view('guest.rates');
    })->name('guest.rates');

    // Bookings
    Route::get('/guest/bookings', [GuestBookingController::class, 'index'])
        ->name('guest.bookings.index');
    Route::get('/guest/calendar', [GuestBookingController::class, 'calendar'])
        ->name('guest.bookings.calendar');

    // Facilities Utilization Form (Guest → GSU)
    Route::get('/guest/requests/facilities', [GuestFormController::class, 'createFacilities'])
        ->name('guest.requests.facilities.create');
    Route::post('/guest/requests/facilities', [GuestFormController::class, 'storeFacilities'])
        ->name('guest.requests.facilities.store');
    Route::get('/guest/requests/facilities/index', [GuestFormController::class, 'indexFacilities'])
        ->name('guest.requests.facilities.index');
    Route::get('/guest/requests/facilities/{submission}', [GuestFormController::class, 'showFacilities'])
        ->name('guest.requests.facilities.show');

    Route::post(
        '/guest/requests/facilities/{submission}/cancel',
        [GuestFormController::class, 'cancelFacilities']
    )->name('guest.requests.facilities.cancel');
});