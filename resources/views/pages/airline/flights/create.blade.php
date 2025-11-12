<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New Flight') }}
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
                    <h3 class="text-lg font-semibold mb-4">Flight Information</h3>

                    <form method="POST" action="{{ route('maskapai.flights.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Flight Number -->
                            <div>
                                <label for="flight_number" class="block text-sm font-medium mb-1">Flight Number</label>
                                <input id="flight_number" type="text" name="flight_number"
                                    value="{{ old('flight_number') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                @error('flight_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium mb-1">Price (Rp)</label>
                                <input id="price" type="number" name="price" value="{{ old('price') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                @error('price')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure Airport -->
                            <div>
                                <label for="departure_airport_id" class="block text-sm font-medium mb-1">Departure
                                    Airport</label>
                                <select id="departure_airport_id" name="departure_airport_id"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                    <option value="">-- Select Departure --</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}" {{ old('departure_airport_id') == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->code }} — {{ $airport->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('departure_airport_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Airport -->
                            <div>
                                <label for="arrival_airport_id" class="block text-sm font-medium mb-1">Arrival
                                    Airport</label>
                                <select id="arrival_airport_id" name="arrival_airport_id"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                    <option value="">-- Select Arrival --</option>
                                    @foreach ($airports as $airport)
                                        <option value="{{ $airport->id }}" {{ old('arrival_airport_id') == $airport->id ? 'selected' : '' }}>
                                            {{ $airport->code }} — {{ $airport->city }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('arrival_airport_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Departure Time -->
                            <div>
                                <label for="departure_time" class="block text-sm font-medium mb-1">Departure
                                    Time</label>
                                <input id="departure_time" type="datetime-local" name="departure_time"
                                    value="{{ old('departure_time', isset($data->departure_time) ? \Carbon\Carbon::parse($data->departure_time)->format('Y-m-d\TH:i') : '')}}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                @error('departure_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Arrival Time -->
                            <div>
                                <label for="arrival_time" class="block text-sm font-medium mb-1">Arrival Time</label>
                                <input id="arrival_time" type="datetime-local" name="arrival_time"
                                    value="{{ old('arrival_time', isset($data->arrival_time) ? \Carbon\Carbon::parse($data->arrival_time)->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                @error('arrival_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Total Seats -->
                            <div>
                                <label for="total_seats" class="block text-sm font-medium mb-1">Total Seats</label>
                                <input id="total_seats" type="number" name="total_seats" min="1"
                                    value="{{ old('total_seats') }}"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                @error('total_seats')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium mb-1">Status</label>
                                <select id="status" name="status"
                                    class="w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900"
                                    required>
                                    <option value="active">Active</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="flex justify-end">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                                Create Flight
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>