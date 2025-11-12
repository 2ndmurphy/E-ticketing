<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Flight') }}
            </h2>
            <a href="{{ route('maskapai.flights.index') }}" 
               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Back to Flights
            </a>
        </div>
    </x-slot>

    <div class="p-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Update Flight Information</h3>

                    <form method="POST" action="{{ route('maskapai.flights.update', $flight->id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Flight Number -->
                            <div>
                                <label for="flight_number" class="block text-sm font-medium mb-1">Flight Number</label>
                                <input id="flight_number" type="text" name="flight_number" value="{{ old('flight_number', $flight->flight_number) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium mb-1">Price (Rp)</label>
                                <input id="price" type="number" name="price" value="{{ old('price', $flight->price) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                            </div>

                            <!-- Departure Airport -->
                            <div>
                                <label for="departure_airport_id" class="block text-sm font-medium mb-1">Departure Airport</label>
                                <select id="departure_airport_id" name="departure_airport_id"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}" 
                                            {{ $flight->departure_airport_id == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->code }} — {{ $airport->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Arrival Airport -->
                            <div>
                                <label for="arrival_airport_id" class="block text-sm font-medium mb-1">Arrival Airport</label>
                                <select id="arrival_airport_id" name="arrival_airport_id"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}" 
                                            {{ $flight->arrival_airport_id == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->code }} — {{ $airport->city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Departure Time -->
                            <div>
                                <label for="departure_time" class="block text-sm font-medium mb-1">Departure Time</label>
                                <input id="departure_time" type="datetime-local" name="departure_time"
                                    value="{{ old('departure_time', $flight->departure_time->format('Y-m-d\TH:i')) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label for="arrival_time" class="block text-sm font-medium mb-1">Arrival Time</label>
                                <input id="arrival_time" type="datetime-local" name="arrival_time"
                                    value="{{ old('arrival_time', $flight->arrival_time->format('Y-m-d\TH:i')) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                            </div>

                            <!-- Total Seats -->
                            <div>
                                <label for="total_seats" class="block text-sm font-medium mb-1">Total Seats</label>
                                <input id="total_seats" type="number" name="total_seats" min="1" 
                                    value="{{ old('total_seats', $flight->total_seats) }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1">Status</label>
                                <select id="status" name="status"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900" required>
                                    <option value="active" {{ $flight->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="cancelled" {{ $flight->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
