<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Penerbangan') }}
            </h2>
            <a href="{{ route('maskapai.flights.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Back to Flights
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flight Summary -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Flight Information</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p><span class="font-medium">Flight Number:</span> {{ $flight->flight_number }}</p>
                            <p><span class="font-medium">Route:</span> 
                                {{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}
                            </p>
                            <p><span class="font-medium">Departure Time:</span> {{ $flight->departure_time->format('Y-m-d H:i') }}</p>
                            <p><span class="font-medium">Arrival Time:</span> {{ $flight->arrival_time->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p><span class="font-medium">Price:</span> Rp{{ number_format($flight->price, 0, ',', '.') }}</p>
                            <p><span class="font-medium">Total Seats:</span> {{ $flight->total_seats }}</p>
                            <p><span class="font-medium">Available Seats:</span> {{ $availableSeats ?? '-' }}</p>
                            <p><span class="font-medium">Status:</span> 
                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $flight->status === 'active' 
                                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' 
                                        : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                    {{ ucfirst($flight->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('maskapai.flights.edit', $flight->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700">
                    Edit Flight
                </a>

                <form action="{{ route('maskapai.flights.destroy', $flight->id) }}" method="POST" onsubmit="return confirm('Cancel this flight?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-md shadow hover:bg-red-700">
                        Cancel Flight
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
