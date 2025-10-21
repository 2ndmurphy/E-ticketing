<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Flight Management') }}
        </h2>
    </x-slot>

    <div class="p-8">
        <a href="{{ route('maskapai.flights.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-md shadow hover:bg-blue-700 mb-4">
            + Add New Flight
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
            <form method="GET" action="{{ route('maskapai.flights.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" placeholder="Search flight..." value="{{ request('search') }}"
                    class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                <select name="status" class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm">
                    <option value="" {{ request('status') == '' ? 'selected' : '' }}>All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="px-3 py-2 bg-gray-700 text-white rounded-md text-sm">Filter</button>
            </form>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
            <table class="min-w-full text-sm text-left">
                <thead
                    class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">Flight No</th>
                        <th class="px-4 py-3">Route</th>
                        <th class="px-4 py-3">Departure Time</th>
                        <th class="px-4 py-3">Seats</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-gray-600 dark:text-gray-400">
                    @foreach ($flights as $flight)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900 transition">
                                        <td class="px-4 py-3 font-medium">{{ $flight->flight_number }}</td>
                                        <td class="px-4 py-3">
                                            {{ $flight->departureAirport->code }} → {{ $flight->arrivalAirport->code }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $flight->departure_time->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $flight->seatAvailability->available_seats ?? $flight->total_seats }}
                                        </td>
                                        <td class="px-4 py-3 capitalize">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                                                                {{ $flight->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                        'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $flight->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <a href="{{ route('maskapai.flights.show', $flight) }}"
                                                class="text-blue-600 hover:text-blue-800 font-medium mr-3">View</a>
                                            <a href="{{ route('maskapai.flights.edit', $flight) }}"
                                                class="text-blue-600 hover:text-blue-800 font-medium mr-3">Edit</a>
                                            <form action="{{ route('maskapai.flights.destroy', $flight->id) }}" method="POST"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium"
                                                    onclick="return confirm('Are you sure you want to cancel this flight?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>