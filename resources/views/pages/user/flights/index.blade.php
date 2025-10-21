<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cari Penerbangan') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Flight Search Form -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <form method="GET" action="{{ route('user.flights.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- From Airport -->
                    <div>
                        <label for="from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                        <select id="from" name="from" required
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select Origin --</option>
                            @foreach ($airports as $airport)
                                <option value="{{ $airport->id }}" {{ request('from') == $airport->id ? 'selected' : '' }}>
                                    {{ $airport->city }} ({{ $airport->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Airport -->
                    <div>
                        <label for="to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                        <select id="to" name="to" required
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">-- Select Destination --</option>
                            @foreach ($airports as $airport)
                                <option value="{{ $airport->id }}" {{ request('to') == $airport->id ? 'selected' : '' }}>
                                    {{ $airport->city }} ({{ $airport->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departure Date</label>
                        <input type="date" id="date" name="date" value="{{ request('date') }}"
                            class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Search Flights
                        </button>
                    </div>
                </form>
            </div>

            <!-- Flight Results -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                @if ($flights->isEmpty())
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        {{ request('from') && request('to') 
                            ? 'No flights found for the selected route or date.' 
                            : 'Please select your route to see available flights.' }}
                    </div>
                @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($flights as $flight)
                            <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $flight->airline->name }} — {{ $flight->flight_number }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})
                                        →
                                        {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }})
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Depart: {{ $flight->departure_time->format('Y-m-d H:i') }} |
                                        Arrive: {{ $flight->arrival_time->format('Y-m-d H:i') }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-800 dark:text-gray-100">
                                        Rp{{ number_format($flight->price, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Seats: {{ $flight->seatAvailability->available_seats ?? $flight->total_seats }}
                                    </p>
                                    <a href="{{ route('user.flights.show', $flight->id) }}"
                                       class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4">
                        {{ $flights->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
