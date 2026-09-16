<div class="space-y-5">

    {{-- Nagłówek rozdzielnicy --}}
    <div
        class="p-4 flex flex-col sticky top-0 z-100 sm:flex-row sm:flex-wrap items-start sm:items-center justify-between gap-5 w-full p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">

        <div class="order-1 flex flex-wrap items-center gap-6 sm:gap-8 w-full sm:w-auto">
            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Rozdzielnica
                </p>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ $switchboard->name }}
                </h2>
            </div>

            <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>

            <div>
                <p class="mb-1 text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Napięcie
                </p>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ (float) $switchboard->protocol->voltage . ' V' }}
                </h2>
            </div>
            <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                    Typ sieci:
                </label>
                <input type="text" wire:change="saveJSON('network_type', $event.target.value)"
                    wire:model ="networkType" style="text-transform:uppercase"
                    class="block w-24 rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
            </div>
            <div class="hidden sm:block w-px h-8 bg-gray-200 dark:bg-gray-700"></div>
        </div>



        <div class="order-2 sm:order-3 flex items-center gap-3 w-full sm:w-auto justify-start sm:justify-end">

            <button type="button" x-data x-on:click="window.history.back()"
                class="sm:inline-flex hidden items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Powrót
            </button>
            <a href="{{ route('protocol.createSwitchboard', ['switchboard' => $switchboard->id]) }}" wire:navigate
                class="sm:inline-flex hidden items-center justify-center px-4 py-2 text-sm font-medium
                   text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200
                   dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600
                   dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700
                   ">

                Przejdź do rozdźielnicy
            </a>

            <button type="button" wire:click="addRoom" wire:loading.attr="disabled" wire:target="addRoom"
                class="cursor-pointer inline-flex items-center justify-center gap-2 w-full sm:w-auto
                   rounded-lg bg-gray-900 px-4 py-2 shadow-sm
                   text-sm font-semibold text-white
                   hover:bg-gray-700
                   focus:outline-none focus:ring-4 focus:ring-gray-300
                   disabled:cursor-not-allowed disabled:opacity-60
                   dark:bg-blue-600 dark:hover:bg-blue-700
                   dark:focus:ring-blue-800
                   ">
                <span class="text-lg leading-none">+</span>
                Dodaj pomieszczenie
            </button>

        </div>

    </div>
    <div
        class="flex flex-col sm:flex-row sm:flex-wrap items-start sm:items-center justify-between gap-5 w-full p-5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
        <div class="order-3 sm:order-2 w-full sm:flex-1 flex justify-center mt-2 sm:mt-0">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 w-full sm:w-auto">
                @forelse ($availableBreakers as $breaker)
                    <div class="flex items-center">
                        <label for="default-checkbox-{{ $breaker->id }}"
                            class="select-none ms-2 text-sm font-medium p">
                            {{ $breaker->name }} {{ $breaker->characteristic }}{{ (float) $breaker->rated_current }}
                        </label>
                    </div>
                @empty
                    <p class="col-span-full text-center text-sm text-gray-500">
                        Brak dostępnych zabezpieczeń
                    </p>
                @endforelse
                <select wire:change="addCircuit($event.target.value)"
                    class="block w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-900 sm:w-72">
                    <option value="">
                        Dodaj dodatkowy bezpiecznik
                    </option>

                    @foreach ($allCircuitBreakers as $allCircuitBreaker)
                        <option value="{{ $allCircuitBreaker->id }}">
                            {{ $allCircuitBreaker->name }}
                            {{ $allCircuitBreaker->characteristic }}
                            {{ (float) $allCircuitBreaker->rated_current }} A
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <div
        class="w-full overflow-hidden rounded-xl
               border border-gray-200 bg-white shadow-sm
               dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">

            <table class="w-full md:min-w-[980px] table-fixed text-center text-xs">


                <thead class="bg-gray-800 text-xs font-semibold tracking-wide text-white dark:bg-gray-900">
                    <tr>
                        {{-- Ukryte na telefonie --}}
                        <th scope="col" class="hidden md:table-cell w-12 px-1.5 py-2.5">Lp.</th>
                        <th scope="col" class="hidden md:table-cell w-14 px-1.5 py-2.5">Symbol</th>

                        {{-- Widoczne na telefonie --}}
                        <th scope="col" class="w-[35%] md:w-54 px-2 py-2.5">Badany punkt</th>
                        <th scope="col" class="w-[30%] md:w-44 px-2 py-2.5">Wyłącznik</th>

                        {{-- Ukryte na telefonie --}}
                        <th scope="col" class="hidden md:table-cell w-24 px-1.5 py-2.5">Typ</th>
                        <th scope="col" class="hidden md:table-cell w-20 px-1.5 py-2.5">In [A]</th>
                        <th scope="col" class="hidden md:table-cell w-20 px-1.5 py-2.5">Ia [A]</th>

                        {{-- Widoczne na telefonie --}}
                        <th scope="col" class="w-[20%] md:w-20 px-1.5 py-2.5">Zs [&#937;]</th>

                        {{-- Ukryte na telefonie --}}
                        <th scope="col" class="hidden md:table-cell w-20 px-1.5 py-2.5">Za [&#937;]</th>
                        <th scope="col" class="hidden md:table-cell w-20 px-1.5 py-2.5">Ik</th>
                        <th scope="col" class="hidden md:table-cell w-24 px-1.5 py-2.5">Ocena</th>

                        {{-- Widoczne na telefonie (przyciski zapisu/usuwania) --}}
                        <th scope="col" class="w-[15%] md:w-16 px-1.5 py-2.5">-</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $itemNumber = 1;
                    @endphp
                    @forelse ($switchboard->rooms as $room)

                        {{-- Nagłówek pomieszczenia --}}
                        <tr class="bg-gray-100 dark:bg-gray-700/60">
                            <td colspan="12" class="px-3 py-2.5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                                    {{-- Lewa strona: Nazwa pomieszczenia, Typ sieci i czerwony kosz --}}
                                    <div class="flex flex-wrap items-center gap-4 w-full sm:w-auto">

                                        {{-- Pomieszczenie --}}
                                        <div class="flex items-center gap-2">
                                            <label for="room-name-{{ $room->id }}"
                                                class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                                Pomieszczenie:
                                            </label>
                                            <input id="room-name-{{ $room->id }}" type="text"
                                                value="{{ $room->name }}"
                                                wire:change="updateRoom({{ $room->id }}, 'name', $event.target.value)"
                                                class="flex-1 sm:flex-none sm:w-64 rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white">
                                        </div>

                                        {{-- Typ sieci --}}


                                        {{-- Przycisk usunięcia --}}
                                        <button type="button" wire:click="removeRoom({{ $room->id }})"
                                            wire:loading.attr="disabled" wire:target="removeRoom({{ $room->id }})"
                                            wire:confirm="Czy chcesz usunąć pomieszczenie wraz z jego elementami"
                                            title="Usuń pomieszczenie"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1 disabled:opacity-50 disabled:cursor-not-allowed dark:bg-red-900/30 dark:text-red-400 dark:hover:bg-red-900/50  shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                        <div class="w-full border-t border-gray-200 pt-3 dark:border-gray-600">
                                            <p class="mb-2 text-xs font-semibold text-gray-700 dark:text-gray-200">
                                                Dostępne bezpieczniki:
                                            </p>


                                            <div class="flex flex-wrap gap-4">
                                                @foreach ($availableBreakers as $breaker)
                                                    <label
                                                        wire:key="room-{{ $room->id }}-breaker-{{ $breaker->id }}"
                                                        class="inline-flex items-center gap-2">
                                                        <input type="checkbox" value="{{ $breaker->id }}"
                                                            wire:model="roomSelectedBreakerIds.{{ $room->id }}"
                                                            wire:change="syncRoomBreakers({{ $room->id }})"
                                                            class="h-4 w-4 rounded border-gray-300">

                                                        <span class="text-xs text-gray-700 dark:text-gray-200">
                                                            {{ $breaker->name }}
                                                            {{ $breaker->characteristic }}
                                                            {{ (float) $breaker->rated_current }} A
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>

                                    {{-- Prawa strona: Checkbox siły i przycisk dodawania gniazdka --}}
                                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">

                                        {{-- Checkbox 400V --}}
                                        {{-- <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                            <input type="checkbox" @checked($room->havePower)
                                                wire:change="updateRoom({{ $room->id }}, 'havePower', $event.target.checked)"
                                                class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-800 dark:ring-offset-gray-800 ">
                                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                                                Siła (400V)
                                            </span>
                                        </label> --}}

                                        {{-- Przycisk Dodaj gniazdko --}}
                                        <button type="button" wire:click="addItem({{ $room->id }})"
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center justify-center gap-1 w-full sm:w-auto rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-blue-900 ">
                                            <span class="text-sm leading-none">+</span>
                                            Dodaj gniazdko
                                        </button>
                                    </div>

                                </div>
                            </td>
                        </tr>
                        {{-- Istniejące elementy --}}
                        @foreach ($room->items as $item)
                            <tr wire:key="item-{{ $item->id }}"
                                class="bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/30 ">

                                <td class="hidden md:table-cell px-1 py-2 font-medium text-gray-900 dark:text-white">
                                    {{ $itemNumber++ }}
                                </td>
                                <td class="hidden md:table-cell px-1 py-2 font-semibold text-gray-900 dark:text-white">
                                    {{ 'GO' . $loop->iteration }}
                                </td>

                                <td class="px-2 py-2 text-center break-words">
                                    <input type="text"
                                        wire:change="patchMeasurement({{ $item->id }}, 'name', $event.target.value)"
                                        value="{{ $item->name }}"
                                        class="cursor-text w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>

                                <td class="px-1.5 py-2">
                                <select
                                    wire:key="select-item-{{ $item->id }}-{{ $item->circuit_breaker_catalog_id }}"
                                    wire:change="patchMeasurement({{ $item->id }}, 'circuit_breaker_catalog_id', $event.target.value)"
                                    class="w-full rounded-md border-blue-200 bg-white px-1.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-gray-800 dark:text-white sm:px-2.5">
                                    <option value="">Wybierz...</option>
                                    
                                    @php
                                        $roomBreakerIds = $roomSelectedBreakerIds[$room->id] ?? [];
                                        
                                        if ($item->circuit_breaker_catalog_id && !in_array($item->circuit_breaker_catalog_id, $roomBreakerIds)) {
                                            $roomBreakerIds[] = $item->circuit_breaker_catalog_id;
                                        }
                                    @endphp

                                    @foreach ($switchboard->circuitBreakers->whereIn('id', $roomBreakerIds)->unique(fn($breaker) => $breaker->name . '|' . $breaker->characteristic . '|' . $breaker->rated_current)->values() as $circuitBreaker)
                                        <option value="{{ $circuitBreaker->id }}" @selected($item->circuit_breaker_catalog_id == $circuitBreaker->id)>
                                            {{ $circuitBreaker->name }} ({{ $circuitBreaker->characteristic }}{{ (float) $circuitBreaker->rated_current }} A)
                                        </option>
                                    @endforeach
                                </select>
                                </td>

                                <td class="hidden md:table-cell px-1.5 py-2">
                                    {{ $item->circuitBreaker?->characteristic ?? '—' }}
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2">
                                    {{ str_replace('.', ',', (float) $item->circuitBreaker?->rated_current) }}
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2">
                                    {{ $item->calculated_current !== null ? str_replace('.', ',', (float) $item->calculated_current) : '—' }}
                                </td>

                                <td class="px-1.5 py-2 font-medium">
                                    <input type="number" step="0.01"
                                        wire:change="patchMeasurement({{ $item->id }}, 'measure_impedance', $event.target.value)"
                                        value="{{ $item->measure_impedance }}"
                                        class="cursor-text w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>

                                <td class="hidden md:table-cell px-1.5 py-2">
                                    {{ $item->calculated_impedance !== null ? str_replace('.', ',', (float) $item->calculated_impedance) : '—' }}
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2">
                                    {{ $item->current_ik !== null ? str_replace('.', ',', (float) $item->current_ik) : '—' }}
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2">
                                    <div class="flex flex-col gap-1.5">
                                        @if ($item->grade === null)
                                            <span
                                                class="inline-flex items-center justify-center rounded-md bg-gray-100 px-2 py-1 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak
                                                danych</span>
                                        @elseif ($item->grade)
                                            <span
                                                class="inline-flex items-center justify-center rounded-md bg-green-100 px-2 py-1 text-[11px] font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">Pozytywna</span>
                                        @else
                                            <span
                                                class="inline-flex items-center justify-center rounded-md bg-red-100 px-2 py-1 text-[11px] font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">Negatywna</span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <button type="button" wire:click="deleteItem({{ $item->id }})"
                                        wire:confirm="Czy na pewno chcesz usunąć ten punkt?"
                                        class="rounded-md px-2 py-1 text-[11px] font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20 ">
                                        Usuń
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Formularz dodawania --}}
                        @if ($addingToRoomId === $room->id)
                            <tr wire:key="new-item-form-{{ $room->id }}"
                                class="bg-blue-50/80 dark:bg-blue-950/30">

                                <td class="hidden md:table-cell px-1 py-2.5 align-top">
                                    {{ $itemNumber++ }}
                                </td>
                                <td class="hidden md:table-cell px-1 py-2.5 align-top">
                                    <input type="text" value="GO" readonly
                                        class="w-11 rounded-md border-blue-200 bg-white px-1 py-1.5 text-center text-xs font-bold text-blue-700 shadow-sm dark:border-blue-900 dark:bg-gray-800 dark:text-blue-400">
                                </td>

                                <td class="px-2 py-2.5 align-top">
                                    <input type="text" wire:model="newItemName" wire:keydown.enter="saveItem"
                                        class="w-full rounded-md border-blue-200 bg-white px-1.5 sm:px-2.5 py-1.5 text-xs text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-gray-800 dark:text-white">
                                    @error('newItemName')
                                        <p
                                            class="mt-1 text-left text-[10px] sm:text-[11px] text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="px-1.5 py-2.5 align-top">
                                    <select wire:model.live="circuitBreakerId"
                                        class="w-full rounded-md border-blue-200 bg-white px-1 sm:px-1.5 py-1.5 text-[11px] sm:text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-gray-800 dark:text-white">
                                        <option value="">Wybierz</option>

                                        @foreach ($availableBreakers->whereIn('id', $selectedBreakerIds) as $circuitBreaker)
                                            <option value="{{ $circuitBreaker->id }}">
                                                {{ $circuitBreaker->name }}
                                                ({{ $circuitBreaker->characteristic . $circuitBreaker->rated_current }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('circuitBreakerId')
                                        <p
                                            class="mt-1 text-left text-[10px] sm:text-[11px] text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <input type="text" value="{{ $deviceSymbol }}" readonly placeholder="Typ"
                                        class="w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <input type="number" value="{{ $ratedCurrent }}" readonly placeholder="In"
                                        class="w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <input type="number" value="{{ $calculatedCurrent }}" readonly placeholder="Ia"
                                        class="w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>

                                <td class="px-1.5 py-2.5 align-top">
                                    <input type="number" step="0.01" autocomplete="off"
                                        wire:model.live.blur="measureImpedance" placeholder="Zs"
                                        wire:keydown.enter="saveItem"
                                        class="w-full rounded-md border-blue-200 bg-white px-1 sm:px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-gray-800 dark:text-white">
                                    @error('measureImpedance')
                                        <p
                                            class="mt-1 text-left text-[10px] sm:text-[11px] text-red-600 dark:text-red-400">
                                            {{ $message }}</p>
                                    @enderror
                                </td>

                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <input type="number" value="{{ $calculatedImpedance }}" readonly
                                        placeholder="Za"
                                        class="w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <input type="number" value="{{ $this->currentIk }}" readonly placeholder="Ik"
                                        class="w-full cursor-default rounded-md border-gray-200 bg-gray-100 px-1.5 py-1.5 text-center text-xs font-medium text-gray-700 shadow-inner dark:border-gray-700 dark:bg-gray-900/60 dark:text-gray-300">
                                </td>
                                <td class="hidden md:table-cell px-1.5 py-2.5 align-top">
                                    <div class="flex flex-col gap-1.5">
                                        @if ($this->grade === null)
                                            <span
                                                class="inline-flex w-full items-center justify-center rounded-md bg-gray-100 px-2 py-1.5 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak
                                                danych</span>
                                        @elseif ($this->grade)
                                            <span
                                                class="inline-flex w-full items-center justify-center rounded-md bg-green-100 px-2 py-1.5 text-[11px] font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">Pozytywna</span>
                                        @else
                                            <span
                                                class="inline-flex w-full items-center justify-center rounded-md bg-red-100 px-2 py-1.5 text-[11px] font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">Negatywna</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <button wire:click="cancelAddingItem"
                                        class="rounded-md px-2 py-1 text-[11px] font-semibold text-gray-500 hover:bg-gray-200 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white ">
                                        Anuluj
                                    </button>
                                </td>
                            </tr>
                        @endif

                    @empty
                        <tr>
                            <td colspan="12"
                                class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Brak pomieszczeń
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

    </div>
    <div class="flex justify-center  ">
        <div class="flex flex-col sm:flex-row justify-center gap-3 w-full mb-10">

            <button type="button" wire:click="addRoom" wire:loading.attr="disabled" wire:target="addRoom"
                class="inline-flex w-full sm:w-auto cursor-pointer items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <span class="text-lg leading-none">+</span>
                Dodaj pomieszczenie
            </button>

            <a href="{{ route('protocol.show', ['protocol' => $switchboard->protocol->id]) }}"
                class="inline-flex w-full sm:w-auto cursor-pointer items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <span class="w-[60px] text-center">
                    Dalej
                </span>
            </a>

        </div>

    </div>

</div>
