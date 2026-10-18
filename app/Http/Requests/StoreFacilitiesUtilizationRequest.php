<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Booking;
use App\Models\Facility;
use Carbon\Carbon;

class StoreFacilitiesUtilizationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_activity' => 'required|date|after_or_equal:' . now()->addDays(7)->toDateString(),
            // Optional last day for multi-day events (same start/end time every day), up to 14 days in total
            'date_end'      => [
                'nullable',
                'date',
                'after_or_equal:date_activity',
                function ($attribute, $value, $fail) {
                    $start = $this->input('date_activity');
                    if ($start && $value && Carbon::parse($start)->diffInDays(Carbon::parse($value)) > 13) {
                        $fail('A multi-day event can last at most 14 days.');
                    }
                },
            ],
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i|after:start_time',
            'facility_id'   => [
                'required',
                'exists:facilities,id',
                function ($attribute, $value, $fail) {
                    $date = $this->input('date_activity');
                    $startTime = $this->input('start_time');
                    $endTime = $this->input('end_time');

                    if (!$date || !$startTime || !$endTime) {
                        return; // Other validation rules will handle this
                    }

                    // Check every day of the event (one day unless date_end is given)
                    $lastDay = $this->input('date_end') ?: $date;
                    if (Carbon::parse($lastDay)->lt(Carbon::parse($date)) || Carbon::parse($date)->diffInDays(Carbon::parse($lastDay)) > 13) {
                        return; // date_end rules report this
                    }

                    for ($day = Carbon::parse($date); $day->lte(Carbon::parse($lastDay)); $day->addDay()) {
                        $startDateTime = Carbon::parse($day->toDateString() . ' ' . $startTime);
                        $endDateTime = Carbon::parse($day->toDateString() . ' ' . $endTime);

                        $isOverlapping = Booking::whereHas('facilities', function ($query) use ($value) {
                            $query->where('facility_id', $value);
                        })
                        ->where(function ($query) use ($startDateTime, $endDateTime) {
                            // overlap if booking starts before our end AND booking ends after our start
                            $query->where('start_time', '<', $endDateTime)
                                  ->where('end_time', '>', $startDateTime);
                        })
                        ->whereIn('status', ['reserved'])
                        ->exists();

                        if ($isOverlapping) {
                            $fail($day->toDateString() === $date && $lastDay === $date
                                ? 'The selected facility is already booked for the chosen date and time.'
                                : 'The selected facility is already booked on ' . $day->format('M d, Y') . ' at the chosen time.');
                            return;
                        }
                    }
                }
            ],
            'purpose'       => 'required|string|max:500',
            'qty_monobloc'  => 'nullable|integer|min:0',
            'qty_table'     => 'nullable|integer|min:0',
            'qty_fan'       => 'nullable|integer|min:0',
            'qty_rostrum'   => 'nullable|integer|min:0',
            'qty_flag'      => 'nullable|integer|min:0',
            'qty_sound'     => 'nullable|integer|min:0',
            'qty_led'       => 'nullable|integer|min:0',
            'venue_others'  => 'nullable|string|max:255',

            // Noted by (signatory)
            'noted_signatory_id' => 'nullable|string',
            'noted_signatory_custom' => 'nullable|string|max:255',

            // Payment attachment
            'payment_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
        ];
    }

    public function attributes(): array
    {
        return [
            'date_activity' => 'date of activity',
            'date_end'      => 'end date',
        ];
    }
}