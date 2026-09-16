<div>
    <div class="overflow-x-auto my-4">
        <h2
            class="my-4 border-t border-gray-100 pb-2 text-lg font-semibold text-gray-900 dark:border-gray-700 dark:text-white">
            Badanie stanu izolacji
        </h2>

        <div
            class="w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <table class="w-full text-left text-xs md:min-w-[980px] md:table-fixed md:text-center">

                {{-- Nagłówek desktop --}}
                <thead
                    class="hidden bg-gray-800 text-xs font-semibold tracking-wide text-white dark:bg-gray-900 md:table-header-group">
                    <tr class="h-11">
                        <th scope="col" class="w-12 px-1 py-3 text-center">Lp.</th>
                        <th scope="col" class="w-14 px-1 py-3 text-center">Symbol</th>
                        <th scope="col" class="w-32 px-2 py-3">Badany odcinek</th>
                        <th scope="col" class="w-32 px-2 py-3">Izolacja</th>
                        <th scope="col" class="w-12 px-1 py-3 text-center">s [mm²]</th>
                        <th scope="col" class="w-12 px-1 py-3 text-center">l [m]</th>
                        <th scope="col" class="w-16 px-1 py-3 text-center">t [°C]</th>
                        <th scope="col" class="w-20 px-1 py-3 text-center">Rs [MΩ]</th>
                        <th scope="col" class="w-16 px-1 py-3 text-center">Rsx [MΩ]</th>
                        <th scope="col" class="w-16 px-1 py-3 text-center">K20</th>
                        <th scope="col" class="w-16 px-1 py-3 text-center">Ra [MΩ]</th>
                        <th scope="col" class="w-20 px-1 py-3 text-center">Ocena</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php $lp = 1; @endphp

                    @forelse ($protocol->switchboards as $switchboard)
                        {{-- Nagłówek rozdzielnicy --}}
                        <tr class="bg-gray-100 dark:bg-gray-700/60">
                            <td colspan="12" class="px-3 py-3">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center gap-2 text-left">
                                        <span
                                            class="text-xs font-semibold text-gray-700 dark:text-gray-200">Rozdzielnica:</span>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $switchboard->name }}</span>
                                    </div>

                                    <div class="flex flex-col gap-2 text-left sm:flex-row sm:items-center">
                                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">Układ
                                            sieci:</label>
                                        <select wire:change="setWireSystem({{ $switchboard->id }}, $event.target.value)"
                                            class="block h-9 w-full rounded-md border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:w-48">
                                            <option value="4-wire"
                                                {{ $switchboard->insulationCataloges->count() == 6 ? 'selected' : '' }}>
                                                4 żyły (PEN) - 6 wierszy
                                            </option>
                                            <option value="5-wire"
                                                {{ $switchboard->insulationCataloges->count() == 10 ? 'selected' : '' }}>
                                                5 żył (PE+N) - 10 wierszy
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        @forelse ($switchboard->insulationCataloges as $insulationCataloge)
                            @php
                                $pivot = $insulationCataloge->pivot;
                                $resistance = $pivots[$pivot->id]['resistance'] ?? null;
                                $factor = $pivots[$pivot->id]['factor'] ?? 1;
                                $rsx = $this->temperatureResistance($resistance, $factor);
                                $allowed = $insulationCataloge->resistance_allowed;
                            @endphp
                            {{-- Widok desktop --}}
                            <tr wire:key="row-{{ $pivot->id }}"
                                class="hidden bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/30 md:table-row">
                                <td class="px-1 py-3 text-center font-medium text-gray-900 dark:text-white">
                                    {{ $lp }}</td>
                                <td class="px-1 py-3 text-center font-semibold"></td>

                                <td class="px-1.5 py-2">
                                    <input type="text" wire:model="pivots.{{ $pivot->id }}.section"
                                        wire:change="savePivotField({{ $pivot->id }}, 'section')"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-2 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <select wire:model="pivots.{{ $pivot->id }}.insulation_catalog_id"
                                        wire:change="savePivotField({{ $pivot->id }}, 'insulation_catalog_id')"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                        @foreach ($insulations as $insulation)
                                            <option value="{{ $insulation->id }}">{{ $insulation->insulation }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-1.5 py-2">
                                    <input type="number" step="0.01"
                                        wire:change="savePivotField({{ $pivot->id }}, 'cross_section')"
                                        wire:model="pivots.{{ $pivot->id }}.cross_section"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input type="number" step="0.01"
                                        wire:change="savePivotField({{ $pivot->id }}, 'length')"
                                        wire:model="pivots.{{ $pivot->id }}.length"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input type="number" step="0.01"
                                        wire:change="savePivotField({{ $pivot->id }}, 'temperature')"
                                        wire:model="pivots.{{ $pivot->id }}.temperature"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input type="number" step="0.01"
                                        wire:change="savePivotField({{ $pivot->id }}, 'resistance')"
                                        wire:model="pivots.{{ $pivot->id }}.resistance"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1 py-3 text-center font-medium">{{ $rsx ?? '—' }}</td>

                                <td class="px-1.5 py-2">
                                    <input type="number" step="0.01"
                                        wire:change="savePivotField({{ $pivot->id }}, 'factor')"
                                        wire:model="pivots.{{ $pivot->id }}.factor"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1 py-3 text-center font-semibold">{{ $allowed ?? '—' }}</td>

                                <td class="px-1.5 py-2">
                                    @if ($rsx !== null && $allowed !== null)
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-md px-2 py-1.5 text-[11px] font-semibold {{ $rsx >= $allowed ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ $rsx >= $allowed ? 'Pozytywna' : 'Negatywna' }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-md bg-gray-100 px-2 py-1.5 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Widok mobilny --}}
                            <tr wire:key="row-{{ $pivot->id }}" class="bg-white dark:bg-gray-800 md:hidden border-t-[6px] border-gray-200 dark:border-gray-700">
                                {{-- ZMIANA: p-0 aby tło nagłówka dotykało krawędzi --}}
                                <td colspan="12" class="p-0">
                                    <div class="flex flex-col">
                                        {{-- ZMIANA: Dodane klasy tła identyczne jak dla rozdzielnicy + padding tylko na ten header --}}
                                        <div class="bg-gray-100 px-4 py-2.5 dark:bg-gray-700/60 border-b border-gray-200 dark:border-gray-700">
                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Lp. {{ $lp }}</span>
                                        </div>

                                        {{-- ZMIANA: Padding przeniesiony do kontenera z treścią --}}
                                        <div class="p-4 space-y-3">
                                            <div>
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Badany
                                                    odcinek</label>
                                                <input type="text"
                                                    wire:model.change="pivots.{{ $pivot->id }}.section"
                                                    class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>

                                            <div>
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Izolacja</label>
                                                <select
                                                    wire:model.change="pivots.{{ $pivot->id }}.insulation_catalog_id"
                                                    class="block h-9 w-full rounded-md border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                    @foreach ($insulations as $insulation)
                                                        <option value="{{ $insulation->id }}">
                                                            {{ $insulation->insulation }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Przekrój
                                                        s (mm²)</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.change="pivots.{{ $pivot->id }}.cross_section"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Długość
                                                        l (km)</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.change="pivots.{{ $pivot->id }}.length"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Temperatura
                                                        t (°C)</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.change="pivots.{{ $pivot->id }}.temperature"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Rezystancja
                                                        Rs (MΩ)</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.live.debounce.300ms="pivots.{{ $pivot->id }}.resistance"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Współczynnik
                                                        K20</label>
                                                    <input type="number" step="0.01"
                                                        wire:model.live.debounce.300ms="pivots.{{ $pivot->id }}.factor"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            </div>

                                            <div
                                                class="mt-2 grid grid-cols-2 gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-900/30">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-semibold uppercase text-gray-500 dark:text-gray-400">Rsx</span>
                                                    <span
                                                        class="text-xs font-medium text-gray-900 dark:text-gray-300">{{ $rsx ?? '—' }}</span>
                                                </div>

                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-semibold uppercase text-gray-500 dark:text-gray-400">Ra
                                                        (min)</span>
                                                    <span
                                                        class="text-xs font-medium text-gray-900 dark:text-gray-300">{{ $allowed ?? '—' }}</span>
                                                </div>

                                                <div class="flex flex-col">
                                                    <span
                                                        class="text-[10px] font-semibold uppercase text-gray-500 dark:text-gray-400">Ocena</span>
                                                    <div class="mt-1">
                                                        @if ($rsx !== null && $allowed !== null)
                                                            <span
                                                                class="inline-flex items-center justify-center rounded-md px-2 py-1 text-[11px] font-semibold {{ $rsx >= $allowed ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' }}">
                                                                {{ $rsx >= $allowed ? 'Pozytywna' : 'Negatywna' }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center justify-center rounded-md bg-gray-100 px-2 py-1 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            @php $lp++; @endphp
                        @empty
                            <tr>
                                <td colspan="12" class="px-3 py-6 text-center text-sm text-gray-500">
                                    Ta rozdzielnica nie ma przypisanych odcinków badawczych.
                                </td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="12" class="px-3 py-6 text-center text-gray-500">
                                Brak rozdzielnic w tym protokole.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>  