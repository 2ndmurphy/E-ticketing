<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Penerbangan') }}
            </h2>
            <a href="{{ route('user.flights.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flight Summary -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $flight->airline->name }} — {{ $flight->flight_number }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})
                                →
                                {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }})
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $flight->status === 'active' 
                                ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' 
                                : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                            {{ ucfirst($flight->status) }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium">Departure:</span> {{ $flight->departure_time->format('Y-m-d H:i') }}</p>
                            <p><span class="font-medium">Arrival:</span> {{ $flight->arrival_time->format('Y-m-d H:i') }}</p>
                            <p><span class="font-medium">Airline Code:</span> {{ $flight->airline->code ?? '-' }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Price:</span> Rp{{ number_format($flight->price, 0, ',', '.') }}</p>
                            <p><span class="font-medium">Total Seats:</span> {{ $flight->total_seats }}</p>
                            <p><span class="font-medium">Available Seats:</span> {{ $availableSeats ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking CTA -->
            <div class="flex justify-end">
                <a href="{{ route('user.bookings.create', $flight->id) }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    Proceed to Booking
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
