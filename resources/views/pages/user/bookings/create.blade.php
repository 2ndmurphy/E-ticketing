<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Booking Penerbangan') }}
            </h2>
            <a href="{{ route('user.flights.show', $flight->id) }}" 
               class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                ← Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    Flight: {{ $flight->airline->name }} — {{ $flight->flight_number }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    {{ $flight->departureAirport->city }} ({{ $flight->departureAirport->code }})
                    →
                    {{ $flight->arrivalAirport->city }} ({{ $flight->arrivalAirport->code }}) <br>
                    Departure: {{ $flight->departure_time->format('Y-m-d H:i') }}
                </p>

                <form method="POST" action="{{ route('user.bookings.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                    
                    <div id="passenger-list" class="space-y-4">
                        <h4 class="font-medium text-gray-800 dark:text-gray-200">Detail Penumpang</h4>
                        
                        <div class="passenger-item border p-4 rounded-md bg-gray-50 dark:bg-gray-800">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm mb-1 text-gray-400">Nama Lengkap</label>
                                    <input name="passengers[0][name]" type="text" required
                                           class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1 text-gray-400">Email</label>
                                    <input name="passengers[0][email]" type="email"
                                           class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1 text-gray-400">Nomor Kursi</label>
                                    <input name="passengers[0][seat_number]" type="text"
                                           class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="add-passenger"
                        class="px-3 py-2 bg-gray-200 dark:bg-gray-700 text-sm rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-200">
                        + Tambah Penumpang
                    </button>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded-md font-semibold text-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                            Konfirmasi Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('add-passenger').addEventListener('click', () => {
            const list = document.getElementById('passenger-list');
            const index = list.querySelectorAll('.passenger-item').length;
            const template = `
                <div class="passenger-item border p-4 rounded-md bg-gray-50 dark:bg-gray-800 mt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm mb-1 text-gray-400">Nama Lengkap</label>
                            <input name="passengers[${index}][name]" type="text" required
                                   class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-gray-400">Email</label>
                            <input name="passengers[${index}][email]" type="email"
                                   class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm mb-1 text-gray-400">Nomor Kursi</label>
                            <input name="passengers[${index}][seat_number]" type="text"
                                   class="w-full border-gray-300 dark:border-gray-700 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>`;
            list.insertAdjacentHTML('beforeend', template);
        });
    </script>
</x-app-layout>
