<div class="flex items-center justify-center min-h-[80vh] sm:p-0 p-4">
    <div
        class="w-full md:max-w-5xl p-0 md:p-8 lg:p-10 bg-white shadow-none md:shadow-lg
               rounded-none md:rounded-2xl dark:bg-gray-800 border-0 md:border
               border-gray-100 dark:border-gray-700">
        <h2 class="mb-6 text-xl md:text-2xl font-bold text-center text-gray-800 dark:text-white">
            Dodaj pomiar
        </h2>

        <form wire:submit="save" class="space-y-5">
            @csrf




            {{-- Dane klienta --}}
            <div class="pt-2">
                <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Dane klienta
                </h3>

                <div class="flex flex-wrap gap-8 mb-6 px-1">
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Imię
                        </p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $client_name }}
                        </p>
                    </div>

                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Nazwisko
                        </p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $client_surname }}
                        </p>
                    </div>
                    <div>
                        <p class="mb-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Telefon
                        </p>
                        <p class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ $client_phone ?? 'Nie podano telefonu'}}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="sm:col-span-2 col-span-1">
                        <label for="client_address"
                            class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Adres klienta
                        </label>
                        <input type="text" id="client_address" wire:model="client_address" name="client_address"
                            placeholder="ul. Przykładowa 12/3, Warszawa" required
                            class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 ">
                    </div>

                </div>
            </div>
            {{-- Daty --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="measurement_date"
                        class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Data pomiaru
                    </label>

                    <input type="date" id="measurement_date" wire:model="measurement_date" name="measurement_date"
                        value="{{ $measurement_date }}" required
                        class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border border-gray-300 rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                               dark:text-white dark:focus:ring-blue-500
                               dark:focus:border-blue-500 ">
                </div>

                <div>
                    <label for="next_measurement_date"
                        class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Następna data pomiaru
                    </label>

                    <input type="date" id="next_measurement_date" wire:model="next_measurement_date"
                        name="next_measurement_date" value="{{ old('next_measurement_date') }}"
                        class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border border-gray-300 rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                               dark:text-white dark:focus:ring-blue-500
                               dark:focus:border-blue-500 ">
                </div>
            </div>
            {{-- Dane budynku --}}
            <div class="pt-2">
                <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Dane budynku
                </h3>

                <div>
                    <label for="building_address"
                        class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Adres budynku
                    </label>

                    <input type="text" id="building_address" name="building_address" wire:model="building_address"
                        value="{{ old('building_address') }}" required
                        class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                               border border-gray-300 rounded-lg focus:ring-blue-500
                               focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                               dark:placeholder-gray-400 dark:text-white
                               dark:focus:ring-blue-500 dark:focus:border-blue-500
                               ">
                </div>
            </div>

            {{-- Dodatkowy tekst --}}
            <div>
                <label for="description" class="block mb-1.5 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Orzeczenie
                </label>

                <textarea id="description" name="description" wire:model="description" rows="5"
                    class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500
                           ">{{ old('description') }}</textarea>
            </div>

            <div class="flex justify-end pt-2">
                <button type="submit"
                    class="w-full md:w-auto min-w-40 px-6 py-2.5 text-sm font-semibold
                           text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700
                           focus:ring-4 focus:outline-none focus:ring-blue-300
                           dark:bg-blue-500 dark:hover:bg-blue-600
                           dark:focus:ring-blue-800 ">
                    Zapisz
                </button>
            </div>
        </form>
    </div>
</div>
