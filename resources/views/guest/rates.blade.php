@extends('layouts.guest')

@section('content')
<div class="bg-white rounded shadow p-8 max-w-7xl mx-auto">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('guest.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium transition">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Header -->
    <div class="text-center mb-8 border-b-2 border-black pb-6">
        <h1 class="text-3xl font-bold text-black mb-2">University of Antique</h1>
        <h2 class="text-xl font-semibold text-gray-700">GENERAL SERVICES UNIT</h2>
        <p class="text-lg text-gray-600 mt-2">Venues/Facilities & Rentals</p>
    </div>

    <!-- Rates Table -->
    <div class="overflow-x-auto mb-8">
        <table class="w-full border-2 border-black text-sm">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="border border-black px-4 py-3 text-left font-semibold">Venue</th>
                    <th class="border border-black px-4 py-3 text-left font-semibold">Capacity</th>
                    <th class="border border-black px-4 py-3 text-left font-semibold">Rate</th>
                    <th class="border border-black px-4 py-3 text-left font-semibold">Facilities Provided/Package</th>
                </tr>
            </thead>
            <tbody>
                <!-- PaghiUsA Hall -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">PaghiUsA Hall</td>
                    <td class="border border-black px-4 py-3">500-600 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 15,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Fully Airconditioned w/sounds</li>
                            <li>700 monoblock chairs</li>
                            <li>10 wooden long tables</li>
                            <li>4 Industrial Fan</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- bUsAlian Hall -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">bUsAlian Hall</td>
                    <td class="border border-black px-4 py-3">70-80 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 10,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Fully Airconditioned</li>
                            <li>90 monoblock chairs</li>
                            <li>2 wooden long tables</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- TiripUnAn Hall -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">TiripUnAn Hall</td>
                    <td class="border border-black px-4 py-3">500-600 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 8,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>600 monoblock chairs</li>
                            <li>4 wooden long tables</li>
                            <li>4 Industrial Fan</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- Balay ni jUAn -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">Balay ni jUAn</td>
                    <td class="border border-black px-4 py-3">50-60 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 8,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Fully Airconditioned</li>
                            <li>60 monoblock chairs</li>
                            <li>2 wooden long tables</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- E-Hub -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">E-Hub</td>
                    <td class="border border-black px-4 py-3">100-150 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 5,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Fully Airconditioned</li>
                            <li>150 monoblock chairs</li>
                            <li>4 wooden long tables</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- New AVR -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">New AVR</td>
                    <td class="border border-black px-4 py-3">80-100 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 5,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Fully Airconditioned</li>
                            <li>100 monoblock chairs</li>
                            <li>4 wooden long tables</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- CEA AVR -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">CEA AVR</td>
                    <td class="border border-black px-4 py-3">60-80 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 2,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>80 monoblock chairs</li>
                            <li>2 square plastic tables</li>
                            <li>2 Industrial Fan</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- CCS AVR -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">CCS AVR</td>
                    <td class="border border-black px-4 py-3">60-80 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 2,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>80 monoblock chairs</li>
                            <li>2 square plastic tables</li>
                            <li>2 Industrial Fan</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- CBA AVR -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">CBA AVR</td>
                    <td class="border border-black px-4 py-3">60-80 persons</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 2,000.00</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>80 monoblock chairs</li>
                            <li>2 square plastic tables</li>
                            <li>2 Industrial Fan</li>
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>

                <!-- Class Room/s -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">Class Room/s</td>
                    <td class="border border-black px-4 py-3">25-30 persons/room</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 50.00/head in a day</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Ceiling Fan</li>
                        </ul>
                    </td>
                </tr>

                <!-- Open Space -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">Open Space</td>
                    <td class="border border-black px-4 py-3">-</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 300.00/day</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Electrical</li>
                        </ul>
                    </td>
                </tr>

                <!-- Track Oval -->
                <tr class="hover:bg-gray-50">
                    <td class="border border-black px-4 py-3 font-semibold">Track Oval</td>
                    <td class="border border-black px-4 py-3">-</td>
                    <td class="border border-black px-4 py-3 font-semibold text-blue-700">PhP 1,000.00/day</td>
                    <td class="border border-black px-4 py-3">
                        <ul class="list-disc list-inside text-xs leading-relaxed">
                            <li>Maximum of 8hrs.</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- LED Wall Notice -->
    <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-500">
        <p class="text-sm font-semibold text-gray-800">
            <span class="text-yellow-700">Note:</span> LED Wall is excluded in the package and available for additional rental.
        </p>
    </div>

    <!-- Footer Signatories -->
    <div class="grid grid-cols-2 gap-8 text-center text-sm text-gray-600 border-t pt-6">
        <div>
            <p class="font-semibold">Prepared by:</p>
            <p class="mt-2">Administrative Officer III</p>
        </div>
        <div>
            <p class="font-semibold">Noted by:</p>
            <p class="mt-2">Administrative Officer V</p>
        </div>
    </div>

    <!-- Action Button -->
    <div class="mt-8 text-center">
        <a href="{{ route('guest.requests.facilities.create') }}" class="inline-block px-8 py-3 bg-black text-white rounded-md hover:bg-gray-800 font-semibold transition">
            Book a Facility Now
        </a>
    </div>
</div>
@endsection
