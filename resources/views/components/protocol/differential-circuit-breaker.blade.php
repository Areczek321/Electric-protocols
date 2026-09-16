@props(['protocol'])

<div class="my-4 space-y-5">
    <h2
        class="my-4 border-t border-gray-100 pb-2 text-lg font-semibold text-gray-900 dark:border-gray-700 dark:text-white">
        Parametry zabezpieczeń różnicowoprądowych
    </h2>

    <div
        class="w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed text-center text-xs md:min-w-[1180px]">

                <thead
                    class="hidden bg-gray-800 text-xs font-semibold tracking-wide text-white dark:bg-gray-900 md:table-header-group">
                    <tr>
                        <th scope="col" class="w-12 px-1.5 py-2.5">Lp.</th>
                        <th scope="col" class="w-14 px-1.5 py-2.5">Symbol</th>
                        <th scope="col" class="w-52 px-2 py-2.5">Badany punkt</th>
                        <th scope="col" class="w-44 px-2 py-2.5">Wyłącznik</th>
                        <th scope="col" class="w-24 px-1.5 py-2.5">Typ</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">In [mA]</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">Ia [mA]</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">ta [ms]</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">t [ms]</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">Vn [V]</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">V [V]</th>
                        <th scope="col" class="w-24 px-1.5 py-2.5">Ocena</th>
                        <th scope="col" class="w-20 px-1.5 py-2.5">-</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $lp = 1;
                    @endphp

                    @forelse ($protocol->switchboards as $switchboard)
                        <tr wire:key="differential-switchboard-header-{{ $switchboard->id }}"
                            class="bg-gray-100 dark:bg-gray-700/60">
                            <td colspan="13" class="px-3 py-2.5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                                        <span
                                            class="text-xs font-semibold text-gray-700 dark:text-gray-200">Rozdzielnica:</span>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $switchboard->name }}</span>
                                    </div>

                                    <button type="button" wire:click="addBreaker({{ $switchboard->id }})"
                                        wire:loading.attr="disabled" wire:target="addBreaker({{ $switchboard->id }})"
                                        class="inline-flex w-full items-center justify-center gap-1 rounded-md bg-blue-600 px-2.5 py-1.5 text-xs font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-blue-900 sm:w-auto">
                                        <span class="text-sm leading-none">+</span>
                                        Dodaj wyłącznik
                                    </button>
                                </div>
                            </td>
                        </tr>

                        @forelse ($switchboard->differentialBreakers as $differentialBreaker)
                            @php
                                $pivotId = $differentialBreaker->pivot->id;
                                $grade = $differentialBreaker->grade;

                                $gradeText = match ($grade) {
                                    true => 'Pozytywna',
                                    false => 'Negatywna',
                                    default => 'Brak danych',
                                };
                            @endphp

                            {{-- DESKTOP --}}
                            <tr wire:key="differential-desktop-row-{{ $pivotId }}"
                                class="hidden bg-white text-gray-700 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/30 md:table-row">
                                <td class="px-1 py-2 font-medium text-gray-900 dark:text-white">
                                    {{ $lp }}
                                </td>

                                <td class="px-1 py-2 text-center font-bold text-gray-400">
                                    -
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-name-{{ $pivotId }}" type="text"
                                        wire:model="differentialPivots.{{ $pivotId }}.name"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'name', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-2 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <select wire:key="desktop-catalog-{{ $pivotId }}"
                                        wire:model="differentialPivots.{{ $pivotId }}.differential_breaker_cataloges_id"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'differential_breaker_cataloges_id', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1 py-1.5 text-center text-[11px] text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-xs">
                                        <option value="" disabled>Wybierz...</option>

                                        @foreach ($differentialBreakers as $dbCat)
                                            <option value="{{ $dbCat->id }}">
                                                {{ $dbCat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-type-{{ $pivotId }}" type="text"
                                        wire:model="differentialPivots.{{ $pivotId }}.type"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'type', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-current-{{ $pivotId }}" type="number" step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.current"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'current', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-current-measured-{{ $pivotId }}" type="number"
                                        step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.current_measured"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'current_measured', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-time-{{ $pivotId }}" type="number" step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.time"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'time', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-time-measured-{{ $pivotId }}" type="number"
                                        step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.time_measured"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'time_measured', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-safe-voltage-{{ $pivotId }}" type="number"
                                        step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.safe_voltage"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'safe_voltage', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-safe-voltage-measured-{{ $pivotId }}"
                                        type="number" step="0.01"
                                        wire:model="differentialPivots.{{ $pivotId }}.safe_voltage_measured"
                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'safe_voltage_measured', $event.target.value)"
                                        class="block h-9 w-full min-w-0 rounded-md border-gray-300 bg-white px-1.5 py-1.5 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                </td>

                                <td class="px-1.5 py-2">
                                    <input wire:key="desktop-grade-{{ $pivotId }}" type="text" readonly
                                        value="{{ $gradeText }}" @class([
                                            'w-full cursor-default rounded-md border-transparent px-2 py-1.5 text-center text-[11px] font-semibold focus:border-transparent focus:outline-none focus:ring-0',
                                            'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' =>
                                                $grade === true,
                                            'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' =>
                                                $grade === false,
                                            'bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400' =>
                                                $grade === null,
                                        ])>
                                </td>

                                <td class="px-1.5 py-2 text-center">
                                    <button type="button" wire:click="deleteBreaker({{ $pivotId }})"
                                        wire:confirm="Czy na pewno chcesz usunąć ten punkt?"
                                        class="rounded-md px-2 py-1 text-[11px] font-semibold text-red-600 hover:bg-red-50 hover:text-red-700 dark:text-red-400 dark:hover:bg-red-900/20">
                                        Usuń
                                    </button>
                                </td>
                            </tr>

                            {{-- MOBILE --}}
                            <tr wire:key="differential-mobile-row-{{ $pivotId }}"
                                class="bg-white text-left dark:bg-gray-800 md:hidden">
                                <td colspan="13" class="px-4 py-4">
                                    <div class="flex flex-col gap-4">
                                        <div
                                            class="flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-700">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                Lp. {{ $lp }}

                                                <span class="mx-1 text-gray-300 dark:text-gray-600">|</span>

                                                <span class="text-xs text-gray-500">
                                                    Symbol: -
                                                </span>
                                            </div>

                                            <button type="button" wire:click="deleteBreaker({{ $pivotId }})"
                                                wire:confirm="Czy na pewno chcesz usunąć ten punkt?"
                                                class="text-xs font-semibold text-red-600 hover:text-red-700 dark:text-red-400">
                                                Usuń obwód
                                            </button>
                                        </div>

                                        <div class="space-y-3">
                                            <div>
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                    Badany punkt
                                                </label>

                                                <input wire:key="mobile-name-{{ $pivotId }}" type="text"
                                                    wire:model="differentialPivots.{{ $pivotId }}.name"
                                                    wire:change="updateDifferentialBreaker({{ $pivotId }}, 'name', $event.target.value)"
                                                    class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                            </div>

                                            <div class="grid grid-cols-2 gap-3">
                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                        Wyłącznik
                                                    </label>

                                                    <select wire:key="mobile-catalog-{{ $pivotId }}"
                                                        wire:model="differentialPivots.{{ $pivotId }}.differential_breaker_cataloges_id"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'differential_breaker_cataloges_id', $event.target.value)"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                        <option value="">Wybierz...</option>

                                                        @foreach ($differentialBreakers as $dbCat)
                                                            <option value="{{ $dbCat->id }}">
                                                                {{ $dbCat->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label
                                                        class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                        Typ
                                                    </label>

                                                    <input wire:key="mobile-type-{{ $pivotId }}" type="text"
                                                        wire:model="differentialPivots.{{ $pivotId }}.type"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'type', $event.target.value)"
                                                        class="block h-9 w-full rounded-md border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            </div>
                                        </div>

                                        <div
                                            class="mt-1 grid grid-cols-2 gap-x-3 gap-y-4 rounded-lg bg-gray-50 p-3 dark:bg-gray-900/30">
                                            <div class="flex flex-col">
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                    Prąd: In / I
                                                </label>

                                                <div class="flex gap-1">
                                                    <input wire:key="mobile-current-{{ $pivotId }}"
                                                        type="number" step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.current"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'current', $event.target.value)"
                                                        class="h-9 w-1/2 rounded-md border-blue-200 bg-blue-50 px-1 text-center text-xs font-semibold text-blue-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">

                                                    <input wire:key="mobile-current-measured-{{ $pivotId }}"
                                                        type="number" step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.current_measured"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'current_measured', $event.target.value)"
                                                        placeholder="Pomiar"
                                                        class="h-9 w-1/2 rounded-md border-gray-300 bg-white px-1 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            </div>

                                            <div class="flex flex-col">
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                    Czas: ta / t
                                                </label>

                                                <div class="flex gap-1">
                                                    <input wire:key="mobile-time-{{ $pivotId }}" type="number"
                                                        step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.time"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'time', $event.target.value)"
                                                        class="h-9 w-1/2 rounded-md border-blue-200 bg-blue-50 px-1 text-center text-xs font-semibold text-blue-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">

                                                    <input wire:key="mobile-time-measured-{{ $pivotId }}"
                                                        type="number" step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.time_measured"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'time_measured', $event.target.value)"
                                                        placeholder="Pomiar"
                                                        class="h-9 w-1/2 rounded-md border-gray-300 bg-white px-1 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            </div>

                                            <div class="flex flex-col">
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                    Napięcie: Vn / V
                                                </label>

                                                <div class="flex gap-1">
                                                    <input wire:key="mobile-safe-voltage-{{ $pivotId }}"
                                                        type="number" step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.safe_voltage"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'safe_voltage', $event.target.value)"
                                                        class="h-9 w-1/2 rounded-md border-blue-200 bg-blue-50 px-1 text-center text-xs font-semibold text-blue-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-blue-900 dark:bg-blue-950/30 dark:text-blue-300">

                                                    <input wire:key="mobile-safe-voltage-measured-{{ $pivotId }}"
                                                        type="number" step="0.01"
                                                        wire:model="differentialPivots.{{ $pivotId }}.safe_voltage_measured"
                                                        wire:change="updateDifferentialBreaker({{ $pivotId }}, 'safe_voltage_measured', $event.target.value)"
                                                        placeholder="Pomiar"
                                                        class="h-9 w-1/2 rounded-md border-gray-300 bg-white px-1 text-center text-xs text-gray-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                </div>
                                            </div>

                                            <div class="flex flex-col">
                                                <label
                                                    class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                                    Ocena
                                                </label>

                                                <input wire:key="mobile-grade-{{ $pivotId }}" type="text"
                                                    readonly value="{{ $gradeText }}" @class([
                                                        'h-9 w-full cursor-default rounded-md border-transparent px-2 py-1.5 text-center text-[11px] font-semibold focus:border-transparent focus:outline-none focus:ring-0',
                                                        'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400' =>
                                                            $grade === true,
                                                        'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400' =>
                                                            $grade === false,
                                                        'bg-gray-200 text-gray-500 dark:bg-gray-800 dark:text-gray-400' =>
                                                            $grade === null,
                                                    ])>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            @php
                                $lp++;
                            @endphp
                        @empty
                            <tr wire:key="empty-differential-switchboard-{{ $switchboard->id }}">
                                <td colspan="13"
                                    class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Brak dodanych punktów.
                                </td>
                            </tr>
                        @endforelse
                    @empty
                        <tr>
                            <td colspan="13"
                                class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Brak rozdzielnic.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
