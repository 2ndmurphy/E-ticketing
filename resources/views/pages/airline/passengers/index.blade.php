<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Passenger Manifest') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Filter / Flight Selection -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select name="flight_id"
                            class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm w-full sm:w-80">
                            <option value="">-- Select Flight --</option>
                            @foreach ($flights as $flight)
                                <option value="{{ $flight->id }}" 
                                    {{ $selectedFlightId == $flight->id ? 'selected' : '' }}>
                                    {{ $flight->flight_number }} — 
                                    {{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }} 
                                    ({{ $flight->departure_time->format('Y-m-d H:i') }})
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="px-3 py-2 bg-gray-700 text-white rounded-md text-sm hover:bg-gray-800">
                            Load Passengers
                        </button>
                    </div>

                    @if ($selectedFlightId && $passengers->isNotEmpty())
                        <a href="{{ route('maskapai.passengers.export', $selectedFlightId) }}"
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-md text-sm font-medium hover:bg-green-700">
                            Export CSV
                        </a>
                    @endif
                </form>
            </div>

            <!-- Passenger Table -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if (!$selectedFlightId)
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Please select a flight to view its passenger manifest.
                    </div>
                @elseif ($passengers->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        No confirmed or completed passengers found for this flight.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold">
                                <tr>
                                    <th class="px-4 py-3">Passenger Name</th>
                                    <th class="px-4 py-3">Email</th>
                                    <th class="px-4 py-3">Seat</th>
                                    <th class="px-4 py-3">Booking Code</th>
                                    <th class="px-4 py-3">Booking Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-600 dark:text-gray-400">
                                @foreach ($passengers as $p)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                        <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                                        <td class="px-4 py-3">{{ $p->email ?? '—' }}</td>
                                        <td class="px-4 py-3">{{ $p->seat_number ?? 'Unassigned' }}</td>
                                        <td class="px-4 py-3">{{ $p->booking->booking_code }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @switch($p->booking->booking_status)
                                                    @case('confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                                    @case('completed') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                                    @case('cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                                @endswitch">
                                                {{ ucfirst($p->booking->booking_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
