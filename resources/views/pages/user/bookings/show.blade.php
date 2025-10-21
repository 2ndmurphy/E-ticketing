<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Booking') }}
            </h2>
            <a href="{{ route('user.bookings.index') }}"
                class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                            Booking Code: {{ $booking->booking_code }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $booking->flight->airline->name }} — {{ $booking->flight->flight_number }}
                        </p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($booking->booking_status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                        @elseif($booking->booking_status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @endif">
                        {{ ucfirst($booking->booking_status) }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-white">
                    <div>
                        <p><span class="font-medium">From:</span> {{ $booking->flight->departureAirport->city }}
                            ({{ $booking->flight->departureAirport->code }})</p>
                        <p><span class="font-medium">To:</span> {{ $booking->flight->arrivalAirport->city }}
                            ({{ $booking->flight->arrivalAirport->code }})</p>
                        <p><span class="font-medium">Departure:</span>
                            {{ $booking->flight->departure_time->format('Y-m-d H:i') }}</p>
                    </div>
                    <div>
                        <p><span class="font-medium">Total Seats:</span> {{ $booking->number_of_seats }}</p>
                        <p><span class="font-medium">Total Price:</span> Rp
                            {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                        <p><span class="font-medium">Booked On:</span> {{ $booking->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>
                </div>

                <h4 class="text-md mt-6 mb-2 font-semibold dark:text-gray-50">Passengers</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700 dark:text-white">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold">Name</th>
                                <th class="px-4 py-2 text-left font-semibold">Email</th>
                                <th class="px-4 py-2 text-left font-semibold">Seat Number</th>
                                <th class="px-4 py-2 text-left font-semibold">Payment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700 text-gray-400">
                            @foreach ($booking->passengers as $p)
                                <tr>
                                    <td class="px-4 py-2">{{ $p->name }}</td>
                                    <td class="px-4 py-2">{{ $p->email ?? '-' }}</td>
                                    <td class="px-4 py-2">{{ $p->seat_number ?? '-' }}</td>
                                    <td class="px-4 py-2">
                                        @if($booking->payment_status === 'unpaid')
                                            <form method="POST" action="{{ route('user.bookings.payment', $booking->id) }}"
                                                class="mt-4">
                                                @csrf
                                                <button type="submit"
                                                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-md">
                                                    💳 Bayar Sekarang
                                                </button>
                                            </form>
                                        @else
                                            <p class="text-green-600 font-semibold mt-4">
                                                ✅ Sukses
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>