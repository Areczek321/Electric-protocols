<div>
    <h2
        class="mb-4 border-t border-gray-100 pb-2 text-lg font-semibold text-gray-900 dark:border-gray-700 dark:text-white">
        Badanie ochrony przed porażeniem samoczynne wyłączenie
    </h2>

    <div class="overflow-x-auto">
        <table class="w-full text-center text-xs table-auto lg:table-fixed lg:min-w-[980px]">

            <thead class="bg-gray-800 text-xs font-semibold tracking-wide text-white dark:bg-gray-900">
                <tr>
                    <th scope="col" class="w-12 hidden px-1.5 py-2.5 sm:table-cell">Lp.</th>
                    <th scope="col" class="w-14 hidden px-1.5 py-2.5 sm:table-cell">Symbol</th>
                    <!-- Widoczne na mobile (1) -->
                    <th scope="col" class="w-auto lg:w-64 px-2 py-2.5">Badany punkt</th>
                    <!-- Widoczne na mobile (2) -->
                    <th scope="col" class="w-auto lg:w-44 px-2 py-2.5">Wyłącznik</th>

                    <th scope="col" class="w-24 hidden px-1.5 py-2.5 sm:table-cell">Typ</th>
                    <th scope="col" class="w-20 hidden px-1.5 py-2.5 md:table-cell">In [A]</th>
                    <th scope="col" class="w-20 hidden px-1.5 py-2.5 md:table-cell">Ia [A]</th>

                    <!-- Widoczne na mobile (3) -->
                    <th scope="col" class="w-auto lg:w-20 px-1.5 py-2.5">Zs [&#937;]</th>

                    <th scope="col" class="w-20 hidden px-1.5 py-2.5 md:table-cell">Za [&#937;]</th>
                    <th scope="col" class="w-20 hidden px-1.5 py-2.5 md:table-cell">Ik</th>
                    <th scope="col" class="w-24 hidden px-1.5 py-2.5 sm:table-cell">Ocena</th>
                    <th scope="col" class="w-16 hidden px-1.5 py-2.5 sm:table-cell">-</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($protocol->rooms as $room)

                    <!-- 1. WERSJA MOBILNA: Widoczna na telefonach (colspan 3), ukryta od breakpointu 'sm' -->
                    <tr class="bg-gray-100 dark:bg-gray-700/60 sm:hidden">
                        <td colspan="3" class="px-3 py-3 text-left">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400">Pomieszczenie:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $room->name }}</span>
                                </div>
                                <!-- ZMIANA: Zamiast "sm:w-full w-fit" dałem po prostu "w-full" -->
                                <a href="{{ route('protocol.formRoom', ['switchboard' => $room->switchboard->id]) }}"
                                    wire:navigate
                                    class="inline-flex w-full items-center justify-center gap-1 rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-blue-900">
                                    <span class="text-sm leading-none">+</span> Edycja
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- 2. WERSJA DESKTOP: Ukryta na telefonach, widoczna od breakpointu 'sm' (colspan 12) -->
                    <tr class="hidden bg-gray-100 dark:bg-gray-700/60 sm:table-row">
                        <td colspan="12" class="px-3 py-3 text-left">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="text-xs font-semibold text-gray-500 dark:text-gray-400">Pomieszczenie:</span>
                                    <span
                                        class="text-sm font-bold text-gray-900 dark:text-white">{{ $room->name }}</span>
                                </div>
                                <a href="{{ route('protocol.formRoom', ['switchboard' => $room->switchboard->id]) }}"
                                    wire:navigate
                                    class="inline-flex w-fit items-center justify-center gap-1 rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-blue-900">
                                    <span class="text-sm leading-none">+</span> Edycja
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- WIERSZE Z DANYMI -->
                    @forelse ($room->items as $item)
                        <tr wire:key="item-{{ $item->id }}"
                            class="bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/30">

                            <td class="hidden px-1 py-2 font-medium text-gray-900 dark:text-white sm:table-cell">
                                {{ $loop->iteration }}</td>
                            <td class="hidden px-1 py-2 font-semibold text-gray-900 dark:text-white sm:table-cell">
                                {{ 'GO' . $loop->iteration }}</td>

                            <!-- Widoczne na mobile (1) -->
                            <td class="px-2 py-2 text-left">{{ $item->name }}</td>

                            <!-- Widoczne na mobile (2) -->
                            <td class="px-1.5 py-2">{{ $item->circuitBreaker?->name ?? '—' }}</td>

                            <td class="hidden px-1.5 py-2 sm:table-cell">
                                {{ $item->circuitBreaker?->characteristic ?? '—' }}</td>
                            <td class="hidden px-1.5 py-2 md:table-cell">
                                {{ str_replace('.', ',', (float) $item->circuitBreaker?->rated_current) }}</td>
                            <td class="hidden px-1.5 py-2 md:table-cell">
                                {{ $item->calculated_current !== null ? str_replace('.', ',', (float) $item->calculated_current) : '—' }}
                            </td>

                            <!-- Widoczne na mobile (3) -->
                            <td class="px-1.5 py-2 font-medium text-gray-900 dark:text-white">
                                {{ $item->measure_impedance !== null ? str_replace('.', ',', (float) $item->measure_impedance) : '—' }}
                            </td>

                            <td class="hidden px-1.5 py-2 md:table-cell">
                                {{ $item->calculated_impedance !== null ? str_replace('.', ',', (float) $item->calculated_impedance) : '—' }}
                            </td>
                            <td class="hidden px-1.5 py-2 md:table-cell">
                                {{ $item->current_ik !== null ? str_replace('.', ',', (float) $item->current_ik) : '—' }}
                            </td>

                            <td class="hidden px-1.5 py-2 sm:table-cell">
                                <div class="flex flex-col items-center gap-1.5">
                                    @if ($item->grade === null)
                                        <span
                                            class="inline-flex items-center justify-center rounded-md bg-gray-100 px-2 py-1 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak</span>
                                    @elseif ($item->grade)
                                        <span
                                            class="inline-flex items-center justify-center rounded-md bg-green-100 px-2 py-1 text-[11px] font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">Pozytywna</span>
                                    @else
                                        <span
                                            class="inline-flex items-center justify-center rounded-md bg-red-100 px-2 py-1 text-[11px] font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">Negatywna</span>
                                    @endif
                                </div>
                            </td>

                            <td class="hidden px-1.5 py-2 sm:table-cell"></td>
                        </tr>
                    @empty
                    @endforelse
                @empty
                @endforelse
            </tbody>
        </table>
    </div>
</div>
