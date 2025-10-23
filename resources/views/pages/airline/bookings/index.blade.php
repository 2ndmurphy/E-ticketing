<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Manajemen Booking') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-12">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
            <form method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Search booking code..."
                    class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm w-full sm:w-48">
                <select name="status" class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
                <button class="px-3 py-2 bg-gray-700 text-white rounded-md text-sm">Filter</button>
            </form>
        </div>
    
        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">Booking Code</th>
                        <th class="px-4 py-3">Flight</th>
                        <th class="px-4 py-3">Passenger(s)</th>
                        <th class="px-4 py-3">Seats</th>
                        <th class="px-4 py-3">Total Price</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3 font-medium">
                                {{ $booking->booking_code }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $booking->flight->departureAirport->code }} → {{ $booking->flight->arrivalAirport->code }}
                                <div class="text-xs text-gray-500 dark:text-gray-600">
                                    {{ $booking->flight->departure_time->format('Y-m-d H:i') }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                @foreach ($booking->passengers as $p)
                                    <div class="text-sm">{{ $p->name }}</div>
                                @endforeach
                            </td>
                            <td class="px-4 py-3">{{ $booking->number_of_seats }}</td>
                            <td class="px-4 py-3">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    @switch($booking->booking_status)
                                        @case('confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                        @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @break
                                        @case('cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                        @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endswitch">
                                    {{ ucfirst($booking->booking_status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('maskapai.bookings.show', $booking->id) }}" 
                                class="text-blue-600 hover:text-blue-800 font-medium">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No bookings found for this airline.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    </div>

</x-app-layout>