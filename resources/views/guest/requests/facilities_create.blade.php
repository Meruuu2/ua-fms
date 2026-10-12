@extends('layouts.guest')

@section('content')
<div class="bg-white rounded shadow p-8 max-w-5xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium transition">
            ← Back to Dashboard
        </a>
    </div>

    {{-- Official UA Header --}}
    <div class="text-center border-2 border-black p-4 mb-6">
        <p class="text-xs">Republic of the Philippines</p>
        <h1 class="text-lg font-bold">UNIVERSITY OF ANTIQUE</h1>
        <p class="text-xs">Sibalom, Antique</p>
        <h2 class="text-base font-semibold mt-4 uppercase">APPLICATION FORM for FACILITIES RENTAL</h2>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-50 text-red-800 border border-red-200 rounded">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('guest.requests.facilities.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Control Number and Date (Auto-generated) --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ctrl. No.:</label>
                <input type="text" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" value="(Auto-generated after submission)">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Application:</label>
                <input type="text" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100" value="{{ date('F d, Y') }}">
            </div>
        </div>

        {{-- Name and Contact --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name: <span class="text-red-500">*</span></label>
                <input type="text" name="client_name" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ auth()->user()->name }}" readonly>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact No.: <span class="text-red-500">*</span></label>
                <input type="text" name="contact_number" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('contact_number', auth()->user()->contact_number ?? '') }}" placeholder="09XX-XXX-XXXX">
            </div>
        </div>

        {{-- Address --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Address: <span class="text-red-500">*</span></label>
            <input type="text" name="address" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('address') }}" placeholder="Enter your complete address">
        </div>

        {{-- Organization/Agency --}}
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Organization/Agency: <span class="text-red-500">*</span></label>
            <input type="text" name="organization" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('organization', auth()->user()->organization_name ?? '') }}">
        </div>

        {{-- Facility, Date, Time --}}
        <div class="grid grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Facility: <span class="text-red-500">*</span></label>
                <select name="facility_id" id="facility_id" required class="w-full border border-gray-300 rounded px-3 py-2">
                    <option value="">Select facility</option>
                    @if(!empty($coreFacilities))
                        @foreach($coreFacilities as $facility)
                            @php
                                $isUnavailable = in_array($facility->availability_status, ['unavailable', 'maintenance'], true);
                                $statusLabel = $facility->availability_status === 'maintenance' ? 'Under Maintenance' : 'Unavailable';
                            @endphp
                            <option value="{{ $facility->id }}"
                                {{ old('facility_id') == $facility->id ? 'selected' : '' }}
                                {{ $isUnavailable ? 'disabled' : '' }}
                                style="{{ $isUnavailable ? 'color:#9CA3AF;' : '' }}"
                                data-rental-fee="{{ $facility->rental_fee ?? 0 }}">
                                {{ $facility->name }}{{ $isUnavailable ? ' (' . $statusLabel . ')' : '' }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Inclusive Date/s of Use: <span class="text-red-500">*</span></label>
                <input type="date" name="date_activity" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('date_activity') }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Time: <span class="text-red-500">*</span></label>
                <div class="flex gap-2">
                    <input type="time" name="start_time" required class="w-1/2 border border-gray-300 rounded px-2 py-2" value="{{ old('start_time') }}">
                    <input type="time" name="end_time" required class="w-1/2 border border-gray-300 rounded px-2 py-2" value="{{ old('end_time') }}">
                </div>
            </div>
        </div>

        {{-- Purpose --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Purpose: <span class="text-red-500">*</span></label>
            <textarea name="purpose" required rows="2" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="State the purpose of facility rental">{{ old('purpose') }}</textarea>
        </div>

        {{-- Facilities/Equipment Table --}}
        <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3 uppercase">Facilities / Equipment to be used</h3>
            <div class="border border-black overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b border-black">
                        <tr>
                            <th class="border-r border-black px-3 py-2 text-left">Facility</th>
                            <th class="border-r border-black px-3 py-2 text-center w-20">Qty</th>
                            <th class="border-r border-black px-3 py-2 text-right w-32">Rental Fee</th>
                            <th class="border-r border-black px-3 py-2 text-center w-28">No. of Days</th>
                            <th class="border-r border-black px-3 py-2 text-center w-28">No. of Hours</th>
                            <th class="px-3 py-2 text-right w-32">Total</th>
                        </tr>
                    </thead>
                    <tbody id="equipmentTableBody">
                        {{-- Main Facility Row --}}
                        <tr id="mainFacilityRow" class="border-b border-black">
                            <td class="border-r border-black px-3 py-2 font-medium" id="facilityNameCell">-</td>
                            <td class="border-r border-black px-3 py-2 text-center">1</td>
                            <td class="border-r border-black px-3 py-2 text-right" id="rentalFeeCell">₱ 0.00</td>
                            <td class="border-r border-black px-3 py-2 text-center" id="daysCell">1</td>
                            <td class="border-r border-black px-3 py-2 text-center" id="hoursCell">0</td>
                            <td class="px-3 py-2 text-right font-semibold" id="totalCell">₱ 0.00</td>
                        </tr>

                        {{-- Equipment Rows --}}
                        @php
                            $equipmentList = [
                                ['name' => 'qty_monobloc', 'label' => 'Monobloc Chair'],
                                ['name' => 'qty_table', 'label' => 'Table'],
                                ['name' => 'qty_fan', 'label' => 'Electric Fan'],
                                ['name' => 'qty_rostrum', 'label' => 'Rostrum'],
                                ['name' => 'qty_flag', 'label' => 'Flag & School Color'],
                                ['name' => 'qty_sound', 'label' => 'Sound System'],
                                ['name' => 'qty_led', 'label' => 'LED Wall'],
                            ];
                        @endphp

                        @foreach($equipmentList as $equipment)
                        <tr class="border-b border-gray-300">
                            <td class="border-r border-black px-3 py-2 text-sm text-gray-700">{{ $equipment['label'] }}</td>
                            <td class="border-r border-black px-3 py-2 text-center">
                                <input type="number" name="{{ $equipment['name'] }}" min="0" value="0" class="w-16 border border-gray-300 rounded px-2 py-1 text-center text-sm">
                            </td>
                            <td class="border-r border-black px-3 py-2 text-right text-gray-500">-</td>
                            <td class="border-r border-black px-3 py-2 text-center text-gray-500">-</td>
                            <td class="border-r border-black px-3 py-2 text-center text-gray-500">-</td>
                            <td class="px-3 py-2 text-right text-gray-500">-</td>
                        </tr>
                        @endforeach

                        {{-- Total Row --}}
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="5" class="border-r border-black px-3 py-2 text-right uppercase">TOTAL</td>
                            <td class="px-3 py-2 text-right text-lg" id="grandTotalCell">₱ 0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Certification Statement --}}
        <div class="mb-6 p-4 bg-gray-50 border border-gray-300 rounded">
            <p class="text-sm mb-3"><strong>I hereby certify that the venue shall be used solely for the purpose stated herein.</strong></p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Client/Representative:</label>
                    <input type="text" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm" value="{{ auth()->user()->name }}">
                </div>
            </div>
        </div>

        {{-- Signatories Section --}}
        <div class="mb-6">
            <h3 class="text-sm font-semibold mb-3 uppercase">Approval Signatories</h3>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Noted by (Immediate Head): <span class="text-red-500">*</span></label>
                <input type="text" name="noted_signatory_custom" required class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('noted_signatory_custom') }}" placeholder="Enter name of organization representative/immediate head">
                <p class="text-xs text-gray-500 mt-1">Enter the name of your organization's representative or immediate head.</p>
            </div>

            <p class="text-xs text-gray-500 italic">Director/Unit Head and OIC-VP for Administration and Finance approval will be processed by the GSU office.</p>
        </div>

        {{-- Payment Attachment --}}
        <div class="mb-6 p-4 border-2 border-blue-500 rounded bg-blue-50">
            <h3 class="text-sm font-semibold mb-2 text-blue-900">PAYMENT RECEIPT <span class="text-red-500">*</span></h3>
            <p class="text-xs text-gray-700 mb-3">Upload your payment receipt (JPG, PNG, or PDF, max 5MB). Payment is required for all guest bookings.</p>
            <input type="file" name="payment_attachment" accept=".jpg,.jpeg,.png,.pdf" required class="w-full border border-gray-300 rounded py-2 px-3 text-sm bg-white">
            <p class="text-xs text-gray-600 mt-2">Your request will be marked as "Pending Payment" until GSU verifies the receipt.</p>
        </div>

        {{-- Additional Attachments --}}
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Supporting Documents (Optional)</label>
            <p class="text-xs text-gray-600 mb-2">Upload additional documents (PDFs only, max 10 files)</p>
            <input type="file" name="attachments[]" multiple accept=".pdf" class="w-full border border-gray-300 rounded py-2 px-3 text-sm bg-white">
        </div>

        {{-- Footer Note --}}
        <div class="mb-6 p-3 bg-yellow-50 border border-yellow-300 rounded">
            <p class="text-xs text-gray-700"><strong>Note:</strong> This permit shall also serve as <strong>GATE PASS</strong> for the organizers.</p>
        </div>

        {{-- Form Footer --}}
        <div class="text-center text-xs text-gray-500 mb-6">
            <p>GSU-FM-004 | Rev.0 / 03-26-26</p>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('guest.dashboard') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" id="submitBtn" class="px-6 py-2 bg-black text-white rounded-md hover:bg-gray-800">
                Submit Application
            </button>
        </div>
    </form>
</div>

<script>
// Calculate rental fee dynamically
document.getElementById('facility_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const facilityName = selectedOption.text;
    const rentalFee = parseFloat(selectedOption.getAttribute('data-rental-fee')) || 0;

    document.getElementById('facilityNameCell').textContent = facilityName !== 'Select facility' ? facilityName : '-';
    document.getElementById('rentalFeeCell').textContent = '₱ ' + rentalFee.toFixed(2);

    calculateTotal();
});

document.querySelector('input[name="date_activity"]').addEventListener('change', calculateTotal);
document.querySelector('input[name="start_time"]').addEventListener('change', calculateTotal);
document.querySelector('input[name="end_time"]').addEventListener('change', calculateTotal);

function calculateTotal() {
    const selectedOption = document.getElementById('facility_id').options[document.getElementById('facility_id').selectedIndex];
    const rentalFee = parseFloat(selectedOption.getAttribute('data-rental-fee')) || 0;

    const startTime = document.querySelector('input[name="start_time"]').value;
    const endTime = document.querySelector('input[name="end_time"]').value;

    let hours = 0;
    if (startTime && endTime) {
        const start = new Date('2000-01-01 ' + startTime);
        const end = new Date('2000-01-01 ' + endTime);
        hours = Math.abs(end - start) / 36e5;
    }

    const days = 1; // Single day booking
    const total = rentalFee * days;

    document.getElementById('daysCell').textContent = days;
    document.getElementById('hoursCell').textContent = hours.toFixed(1);
    document.getElementById('totalCell').textContent = '₱ ' + total.toFixed(2);
    document.getElementById('grandTotalCell').textContent = '₱ ' + total.toFixed(2);
}

// Prevent double submission
const form = document.querySelector('form');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', function() {
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';
    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

    setTimeout(function() {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Submit Application';
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }, 5000);
});
</script>
@endsection
