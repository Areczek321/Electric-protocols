<div>
    <div class="mb-4 flex items-center gap-2 px-2 pt-4 justify-between flex-wrap">
        <div class="w-full h-[2px] border-t border-gray-100 dark:border-gray-700">
        </div>
        <h2 class="pb-2 text-lg font-semibold text-gray-900  dark:text-white">
            Badanie rezystancji izolacji przewodów
        </h2>
        <div class="mb-4 flex items-center gap-2 px-2">
            
            @if (!$isEmbedded)
            <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">
                Układ sieci:
            </label>
            <select wire:model.live="columnCount"
                class="block mr-5 h-8 w-32 rounded-md border-gray-300 bg-white px-2 py-1 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="6" {{  $columnCount <= 10 ? 'selected' : ''  }} >4 żyły (PEN)</option>
                <option value="10"  {{  $columnCount == 10 ? 'selected' : ''  }}>5 żył (PE+N)</option>
            </select>
                <a href="{{ route('protocol.show', ['protocol' => $protocol->id]) }}" wire:navigate
                    class="sm:inline-flex hidden items-center justify-center px-4 py-2 text-sm font-medium
                   text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm
                   hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200
                   dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600
                   dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700
                   ">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Powrót
                </a>
            @endif
        </div>



    </div>

    <div class="overflow-x-auto pb-4">
        @php

            if ($columnCount == 6) {
                $labels = ['L1-L2', 'L1-L3', 'L2-L3', 'L1-PEN', 'L2-PEN', 'L3-PEN'];
            } else {
                $labels = ['L1-L2', 'L1-L3', 'L2-L3', 'L1-PE', 'L2-PE', 'L3-PE', 'L1-N', 'L2-N', 'L3-N', 'N-PE'];
            }
        @endphp
        <table class="w-full border-collapse text-left md:text-center text-xs md:min-w-[980px]">
            <colgroup>
                <col class="hidden md:table-column w-10">
                <col class="w-16 md:w-14">
                <col class="w-full md:w-32">
                @for ($i = 0; $i < $columnCount; $i++)
                    <col class="hidden md:table-column w-14">
                @endfor
                <col class="hidden md:table-column w-14">
                <col class="hidden md:table-column w-16">
                <col class="w-10"> {{-- Kolumna na przycisk usuwania --}}
            </colgroup>

            <thead class="bg-gray-800 text-xs font-semibold tracking-wide text-white dark:bg-gray-900">
                <tr class="h-11">
                    <th scope="col" class="hidden md:table-cell px-1 py-3">Lp.</th>
                    <th scope="col" class="px-2 md:px-1 py-3 text-left md:text-center">Symbol</th>
                    <th scope="col" class="px-2 py-3 text-left md:text-center">Nazwa obwodu</th>

                    @for ($i = 0; $i < $columnCount; $i++)
                        <th scope="col" class="hidden md:table-cell px-1 py-3">{{ $labels[$i] }}</th>
                    @endfor

                    <th scope="col" class="hidden md:table-cell px-1 py-3 text-center">Ra</th>
                    <th scope="col" class="hidden md:table-cell px-1 py-3 text-center">Ocena</th>
                    <th scope="col" class="px-1 py-3 text-center"></th> {{-- Pusty nagłówek dla kosza --}}
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr class="bg-gray-100 dark:bg-gray-700/60">
                    <td colspan="{{ $columnCount + 6 }}" class="px-3 py-3">
                        @if ($isEmbedded)
                            <a href="{{ route('protocol.wire', ['protocol' => $protocol->id]) }}"
                                class="flex h-9 w-full items-center justify-center gap-1.5 rounded-md bg-blue-600 px-3 text-xs font-semibold text-white shadow-sm  hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Przejdź do edycji pomiarów
                            </a>
                        @else
                            <button type="button" wire:click="addEmptyRow"
                                class="flex h-9 w-full items-center justify-center gap-1.5 rounded-md bg-blue-600 px-3 text-xs font-semibold text-white shadow-sm  hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Dodaj dodatkowy obwód
                            </button>
                        @endif
                    </td>
                </tr>

                @forelse ($this->wireResistances() as $wireResistance)
                    <tr wire:key="row-desktop-{{ $wireResistance->id }}"
                        class="bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/30">
                        <td
                            class="hidden md:table-cell px-1 py-3 text-center font-medium text-gray-900 dark:text-white">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-2 md:px-1 py-3 font-semibold text-left md:text-center">OZ{{ $loop->iteration }}
                        </td>

                        <td class="px-2 md:px-1.5 py-2">
                            <input type="text" 
                                value="{{ $resistancesData[$wireResistance->id]['name'] ?? '' }}"
                                wire:change="updateResistance({{ $wireResistance->id }}, 'name', $event.target.value)"
                                @readonly($isEmbedded)
                                class="block cursor-text h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 
                                    read-only:bg-gray-100 read-only:text-gray-500 read-only:shadow-none read-only:focus:border-gray-300 read-only:focus:ring-0
                                    dark:border-gray-600 dark:bg-gray-700 dark:text-white 
                                    dark:read-only:bg-gray-800 dark:read-only:text-gray-400 dark:read-only:border-gray-700">
                        </td>

                        @for ($i = 1; $i <= $columnCount; $i++)
                            <td class="hidden md:table-cell px-0.5 py-2">
                                <input type="number" step="0.01" wire:model="resistancesData.{{ $wireResistance->id }}.section_{{ $i }}" 
                                wire:change="updateResistance({{ $wireResistance->id }}, 'section_{{ $i }}', $event.target.value)"
                                @readonly($isEmbedded) class="block cursor-text h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 read-only:bg-gray-100 read-only:text-gray-500 read-only:shadow-none read-only:focus:border-gray-300 read-only:focus:ring-0 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:read-only:bg-gray-800 dark:read-only:text-gray-400 dark:read-only:border-gray-700">
                            </td>
                        @endfor

                        <td
                            class="hidden md:table-cell px-1 py-3 text-center font-medium text-gray-900 dark:text-gray-300">
                            1,0
                        </td>
                        <td class="hidden md:table-cell px-1 py-3 text-center">
                            <input type="text" readonly value="{{ $wireResistance->grade ?? 'Brak' }}"
                                @class([
                                    'w-full cursor-default rounded-md border-transparent px-2 py-1.5 text-center text-[11px] font-semibold focus:border-transparent focus:outline-none focus:ring-0',
                                    'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' =>
                                        $wireResistance->grade === 'Pozytywna',
                                    'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' =>
                                        $wireResistance->grade === 'Negatywna',
                                    'bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400' =>
                                        $wireResistance->grade === null,
                                ])>
                        </td>

                        {{-- Przycisk USUWANIA wiersza --}}
                        <td class="px-1 py-2 text-center">
                            @if (!$isEmbedded)
                                <button type="button" wire:click="deleteRow({{ $wireResistance->id }})"
                                    wire:confirm="Czy na pewno chcesz usunąć ten wiersz?"
                                    class="inline-flex items-center justify-center rounded p-1 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400 "
                                    title="Usuń obwód">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </td>
                    </tr>

                    {{-- Sekcja mobilna --}}
                    <tr wire:key="row-mobile-{{ $wireResistance->id }}"
                        class="md:hidden bg-gray-50 dark:bg-gray-800/50">
                        <td colspan="4" class="px-4 py-3">
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @for ($i = 1; $i <= $columnCount; $i++)
                                    <div class="flex flex-col gap-1">
                                        <label
                                            class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            {{ $labels[$i - 1] }}
                                        </label>
                                        <input type="number" step="0.01" wire:model.change="resistancesData.{{ $wireResistance->id }}.section_{{ $i }}" @readonly($isEmbedded) class="block h-9 w-full rounded-md border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 read-only:bg-gray-100 read-only:text-gray-500 read-only:shadow-none read-only:focus:border-gray-300 read-only:focus:ring-0 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:read-only:bg-gray-800 dark:read-only:text-gray-400 dark:read-only:border-gray-700">
                                    </div>
                                @endfor
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $columnCount + 6 }}"
                            class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Brak pokoi przypisanych do rozdzielnic w tym protokole. Wiersze wygenerują się automatycznie
                            po dodaniu pokoi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>