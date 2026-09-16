<div class="w-full mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm">


    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold mb-6 text-gray-900 dark:text-white">
            Konfiguracja Rozdzielnicy
        </h2>
        <a href="{{ route('protocol.show', ['protocol' => $switchboard->protocol_id]) }}" wire:navigate
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium
                   text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200
                   dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600
                   dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700
                   ">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Powrót
        </a>
    </div>
    <div
        class="mb-8 p-5 bg-blue-50 dark:bg-blue-900/20
               border border-blue-100 dark:border-blue-800 rounded-lg
               flex flex-col sm:flex-row items-start sm:items-center gap-4 w-full">
        <div>
            <label for="slots-count"
                class="text-center block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ">
                Liczba modułów w rozdzielnicy:
            </label>

            <input type="number" id="slots-count" min="1" max="100" wire:model.live.blur="slotCount"
                @class([
                    'w-32 rounded-md shadow-sm text-lg font-semibold text-center',
                    'dark:bg-gray-800 focus:ring-blue-500 focus:border-blue-500',
                    'border-gray-300 dark:border-gray-600' => !$errors->has('slotCount'),
                    'border-red-500 dark:border-red-500 focus:ring-red-500 focus:border-red-500' => $errors->has(
                        'slotCount'),
                ])>

            @error('slotCount')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">
                    {{ $message }}
                </p>
            @enderror
        </div>

    </div>

    <div class="space-y-4">
        @foreach ($modules as $index => $slot)
            @php
                $slotNumberError = "modules.{$index}.slot_number";
                $slotsOccupationError = "modules.{$index}.slots_occupation";
                $breakerError = "modules.{$index}.breaker_id";

                $hasSlotError =
                    $errors->has($slotNumberError) ||
                    $errors->has($slotsOccupationError) ||
                    $errors->has($breakerError);
            @endphp

            <div wire:key="module-card-{{ $index }}-{{ $slot['slot_number'] }}" @class([
                'p-4 border rounded-lg bg-gray-50 dark:bg-gray-900/50',
                'border-gray-200 dark:border-gray-700' => !$hasSlotError,
                'border-red-300 dark:border-red-800' => $hasSlotError,
            ])>
                {{-- Używamy grid zamiast flex, żeby lepiej kontrolować układ w kolumnach --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                    {{-- Pole (Kolumna 1) --}}
                    <div class="md:col-span-1">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Pole</label>
                        <input type="number" wire:model.live.blur.500ms="modules.{{ $index }}.slot_number"
                            @class([
                                'w-full px-3 py-2 dark:bg-gray-800 rounded-md shadow-sm border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500',
                            ])>
                    </div>

                    {{-- Ilość (Kolumna 2) --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Ilość</label>
                        <input type="number" wire:model.live="modules.{{ $index }}.slots_occupation"
                            @class([
                                'w-full px-3 py-2 dark:bg-gray-800 rounded-md shadow-sm border-gray-300 dark:border-gray-600 focus:ring-blue-500 focus:border-blue-500',
                            ])>
                    </div>

                    {{-- Bezpiecznik (Kolumna 6 ) --}}
                    <div class="md:col-span-4">
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Bezpiecznik</label>
                        <select wire:model.live="modules.{{ $index }}.breaker_id"
                            class="w-full px-3 py-2 dark:bg-gray-800 rounded-md shadow-sm border-gray-300 dark:border-gray-600 text-xs focus:ring-blue-500 focus:border-blue-500 text-gray-700 dark:text-gray-300">
                            <option value="">-- Wybierz --</option>
                            @foreach ($availableBreakers as $breaker)
                                <option value="{{ $breaker->id }}">
                                    {{ $breaker->name }}
                                    ({{ $breaker->characteristic }}{{ (float) $breaker->rated_current }}A)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dodatkowy Input (Kolumna 2) --}}
                    <div class="md:col-span-4">
                        <label
                            class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1 opacity-0">Dodatkowy</label>
                        <input type="text" wire:model.blur="modules.{{ $index }}.custom_name"
                            class="w-full px-3 py-2 text-xs text-gray-900 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:text-white">
                    </div>

                    {{-- Usuwanie (Kolumna 1) --}}
                    <div class="md:col-span-1 flex justify-center">
                        <button type="button" wire:click="removeSlot({{ $index }})"
                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        <button type="button" wire:click="addSlot"
            class=" cursor-pointer w-full py-3 border-2 border-dashed border-gray-300 dark:border-gray-600 
                   rounded-lg text-gray-600 dark:text-gray-400 font-semibold 
                   hover:border-blue-500 hover:text-blue-500 hover:bg-blue-50
                   dark:hover:border-blue-400 dark:hover:text-blue-400 dark:hover:bg-blue-900/20
                    flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Dodaj kolejny moduł
        </button>
    </div>

    <div class="mt-8 flex justify-between">
        <button type="button" wire:click="save" wire:loading.attr="disabled" wire:target="save"
            class=" cursor-pointer px-6 py-2.5 bg-black text-white font-semibold rounded-lg
                   hover:bg-gray-800 dark:bg-blue-600 dark:hover:bg-blue-700
                   disabled:opacity-50 disabled:cursor-not-allowed
                   ">
            <span wire:loading.remove wire:target="save">
                Zapisz całą rozdzielnicę
            </span>

            <span wire:loading wire:target="save">
                Zapisywanie...
            </span>
        </button>
        <div wire:click="saveAndGo"
            class=" cursor-pointer inline-flex items-center justify-center px-4 py-2 text-sm font-medium
                   text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200
                   dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600
                   dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700
                   ">
            Dodaj pomieszczenia
        </div>
    </div>
</div>
