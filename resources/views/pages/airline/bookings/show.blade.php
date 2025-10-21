<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Booking Details') }}
            </h2>
            <a href="{{ route('maskapai.bookings.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Back to Bookings
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Booking Summary Card -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Booking Information</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium">Booking Code:</span> {{ $booking->booking_code }}</p>
                            <p><span class="font-medium">Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @switch($booking->booking_status)
                                        @case('confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @break
                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                        @case('completed') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endswitch">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                            </p>
                            <p><span class="font-medium">Total Price:</span> Rp{{ number_format($booking->total_price, 0, ',', '.') }}</p>
                            <p><span class="font-medium">Seats Booked:</span> {{ $booking->number_of_seats }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Booking Date:</span> {{ $booking->created_at->format('Y-m-d H:i') }}</p>
                            <p><span class="font-medium">Passenger Email:</span> {{ $booking->passenger_email ?? '—' }}</p>
                            <p><span class="font-medium">Last Updated:</span> {{ $booking->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flight Details Card -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Flight Information</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium">Flight Number:</span> {{ $booking->flight->flight_number }}</p>
                            <p><span class="font-medium">Route:</span> 
                                {{ $booking->flight->departureAirport->code }} → {{ $booking->flight->arrivalAirport->code }}
                            </p>
                            <p><span class="font-medium">Departure:</span> {{ $booking->flight->departure_time->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Arrival:</span> {{ $booking->flight->arrival_time->format('Y-m-d H:i') }}</p>
                            <p><span class="font-medium">Price per Seat:</span> Rp{{ number_format($booking->price_per_seat, 0, ',', '.') }}</p>
                            <p><span class="font-medium">Flight Status:</span> {{ ucfirst($booking->flight->status) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Passenger List -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Passenger Details</h3>

                    @if($booking->passengers->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No passenger records found.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold">
                                    <tr>
                                        <th class="px-4 py-3">Name</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Seat</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($booking->passengers as $passenger)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                            <td class="px-4 py-3">{{ $passenger->name }}</td>
                                            <td class="px-4 py-3">{{ $passenger->email ?? '—' }}</td>
                                            <td class="px-4 py-3">{{ $passenger->seat_number ?? 'Unassigned' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update Booking Status -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Update Booking Status</h3>

                    <form method="POST" action="{{ route('maskapai.bookings.update-status', $booking->id) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1">Status</label>
                                <select id="status" name="status" required
                                    class="w-full border-gray-300 dark:border-gray-700 text-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                    <option value="confirmed" {{ $booking->booking_status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="cancelled" {{ $booking->booking_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="completed" {{ $booking->booking_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div>
                                <label for="notes" class="block text-sm font-medium mb-1">Notes</label>
                                <textarea id="notes" name="notes" rows="2"
                                    class="w-full border-gray-300 dark:border-gray-700 text-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"
                                    placeholder="Optional notes (max 500 chars)"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Update Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
