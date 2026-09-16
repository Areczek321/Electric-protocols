<div class="w-full p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
    <h2
        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
        Dane powiązanego klienta
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Imię i Nazwisko
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $protocol->client->name }} {{ $protocol->client->surname }}
            </p>
        </div>

        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Telefon
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $protocol->client->phone }}
            </p>
        </div>

    </div>
</div>
<div class="w-full p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
    <h2
        class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-100 dark:border-gray-700">
        Informacje o pomiarze
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Adres klienta
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ $protocol->client->address }}
            </p>
        </div>

        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Adres budynku
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                <input type="text" wire:change="updateProtocol('building_address')"
                    wire:model="addressBulidngProtocl"
                    class="block px-3.5 py-2.5 w-full text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </p>
        </div>
        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Rodzaj pomiarów:
            </p>
            <input type="text" wire:change="saveJSON('type_of_measurements',$event.target.value)"
                value="{{ $protocol->additional_info['type_of_measurements'] ?? '' }}"
                class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Pogoda:
            </p>
            <input type="text" wire:change="saveJSON('weather',$event.target.value)"
                value="{{ $protocol->additional_info['weather'] ?? '' }}"
                class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </div>
        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400 ">
                Data pomiaru
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                {{ \Carbon\Carbon::parse($protocol->date)->format('d.m.Y') }}
            </p>
        </div>

        <div>
            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Następny pomiar
            </p>
            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                <input type="date" wire:change="updateProtocol('next_date')" wire:model="nextDateBulidngProtocl"
                    class="block w-full sm:w-1/2 px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">
            </p>

        </div>
        <div class="col-span-1 sm:col-span-2">
            <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Instalacja:
            </p>

            <div class="flex flex-col space-y-3 sm:space-y-0 sm:flex-row sm:items-center sm:justify-between w-full mb-4">

                <div class="flex items-center">
                    <input type="radio" value="new" wire:change="saveJSON('installation',$event.target.value)"
                        id="installationNew" name="installation"
                        {{ ($protocol->additional_info['installation'] ?? '') === 'new' ? 'checked' : '' }}
                        class="w-4 h-4 text-neutral-primary border-default-medium bg-white rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                    <label class="select-none ms-2 text-sm font-medium text-heading" for="installationNew">Nowa</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="development" wire:change="saveJSON('installation',$event.target.value)"
                        id="installationDevelopment" name="installation"
                        {{ ($protocol->additional_info['installation'] ?? '') === 'development' ? 'checked' : '' }}
                        class="w-4 h-4 text-neutral-primary border-default-medium bg-white rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                    <label class="select-none ms-2 text-sm font-medium text-heading"
                        for="installationDevelopment">Rozbudowa</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="modifiaction"
                        wire:change="saveJSON('installation',$event.target.value)" id="installationModification"
                        name="installation"
                        {{ ($protocol->additional_info['installation'] ?? '') === 'modifiaction' ? 'checked' : '' }}
                        class="w-4 h-4 text-neutral-primary border-default-medium bg-white rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                    <label class="select-none ms-2 text-sm font-medium text-heading"
                        for="installationModification">Modyfikacja</label>
                </div>

                <div class="flex items-center">
                    <input type="radio" value="existing" wire:change="saveJSON('installation',$event.target.value)"
                        id="installationExisting" name="installation"
                        {{ ($protocol->additional_info['installation'] ?? '') === 'existing' ? 'checked' : '' }}
                        class="w-4 h-4 text-neutral-primary border-default-medium bg-white rounded-full checked:border-brand focus:ring-2 focus:outline-none focus:ring-brand-subtle border border-default appearance-none">
                    <label class="select-none ms-2 text-sm font-medium text-heading"
                        for="installationExisting">Istniejąca</label>
                </div>

            </div>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
        <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
            Napięcie
        </p>
        <p class="text-sm font-semibold text-gray-900 dark:text-white">
            <input type="number" wire:model="voltageProtocl" wire:change="updateProtocol('voltage')"
                value="{{ (float)$protocol->voltage }}"
                class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">
        </p>

    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
        <p class="mb-2 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
            Orzeczenie
        </p>
        <div class="">

            <textarea wire:change="saveJSON('decision',$event.target.value)" rows="5"
                class="block w-full px-3.5 py-2.5 text-sm text-gray-900 bg-gray-50
                           border border-gray-300 rounded-lg resize-y focus:ring-blue-500
                           focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600
                           dark:placeholder-gray-400 dark:text-white
                           dark:focus:ring-blue-500 dark:focus:border-blue-500">{{ $protocol->additional_info['decision'] ?? '' }}</textarea>
        </div>
    </div>
</div>
