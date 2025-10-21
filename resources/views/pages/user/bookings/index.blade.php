<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($bookings->isEmpty())
                        <div class="text-center text-gray-500 dark:text-gray-400 py-8">
                            You haven’t booked any flights yet.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold">Booking Code</th>
                                        <th class="px-4 py-3 text-left font-semibold">Flight</th>
                                        <th class="px-4 py-3 text-left font-semibold">Route</th>
                                        <th class="px-4 py-3 text-left font-semibold">Departure</th>
                                        <th class="px-4 py-3 text-left font-semibold">Status</th>
                                        <th class="px-4 py-3 text-right font-semibold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach ($bookings as $booking)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                            <td class="px-4 py-3 font-medium">{{ $booking->booking_code }}</td>
                                            <td class="px-4 py-3">
                                                {{ $booking->flight->airline->name }}
                                                ({{ $booking->flight->flight_number }})
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $booking->flight->departureAirport->code }}
                                                →
                                                {{ $booking->flight->arrivalAirport->code }}
                                            </td>
                                            <td class="px-4 py-3">
                                                {{ $booking->flight->departure_time->format('Y-m-d H:i') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                    @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                                                    {{ ucfirst($booking->booking_status) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right space-x-2">
                                                <a href="{{ route('user.bookings.show', $booking->id) }}"
                                                   class="text-blue-600 hover:underline">View</a>
                                                @if($booking->booking_status === 'pending')
                                                    <form action="{{ route('user.bookings.cancel', $booking->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="text-red-600 hover:underline"
                                                            onclick="return confirm('Cancel this booking?')">Cancel</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
