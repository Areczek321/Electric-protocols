<div>
    <button class="no-print" onclick="window.print()">
        Drukuj
    </button>

    {{-- STRONA 1 --}}
    <section class="page avoid-break ">
        <h1>Protokół z pomiarów ochronnych</h1>

        <div class="protocol-number">
            PO/{{ $protocol->date->format('d/m/Y') }}/AA
        </div>

        <!-- 1. TABELA SZYROKA (Zleceniodawca i Miejsce) -->
        <table style="width: 100%;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div class="label">Zleceniodawca:</div>
                    <div class="value-space protocol-info">{{ $client->name }}</div>
                    <div class="value-space protocol-info">{{ $client->address }}</div>
                </td>

                <td style="width: 50%; vertical-align: top;">
                    <div class="label">Miejsce przeprowadzenia pomiarów:</div>
                    <div class="value-space protocol-info">{{ $client->name }}</div>
                    <div class="value-space protocol-info">{{ $protocol->building_address }}</div>
                </td>
            </tr>
        </table>

        <!-- 2. TABELA WCIĘTA -->
        <table style="width: 90%; margin: -1px auto 0 auto;">
            <tr>
                <td style="width: 50%; vertical-align: top;">
                    <div class="label">Rodzaj pomiarów:</div>
                    <div class="value-space protocol-info">
                        {{ $protocol->additional_info['type_of_measurements'] ?? '' }}
                    </div>
                </td>

                <td style="width: 50%; vertical-align: top;">
                    <div class="label">Pogoda:</div>
                    <div class="value-space protocol-info">{{ $protocol->additional_info['weather'] ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td style="vertical-align: top;">
                    <div class="label">Data pomiarów:</div>
                    <div class="value-space protocol-info">{{ $protocol->date->format('d/m/Y') }}</div>
                </td>

                <td style="vertical-align: top;">
                    <div class="label">Data następnych pomiarów:</div>
                    <div class="value-space protocol-info">{{ $protocol->next_date?->format('d/m/Y') ?? '' }}</div>
                </td>
            </tr>

            <tr>
                <td colspan="2" style="vertical-align: top;">
                    <div class="label">Instalacja:</div>
                    <div class="checkbox-row" style="padding: 2mm 0;">
                        <span>
                            <span
                                class="checkbox {{ data_get($protocol->additional_info, 'installation') === 'new' ? 'checked' : '' }}"></span>
                            Nowa
                        </span>

                        <span>
                            <span
                                class="checkbox {{ data_get($protocol->additional_info, 'installation') === 'development' ? 'checked' : '' }}"></span>
                            Rozbudowa
                        </span>

                        <span>
                            <span
                                class="checkbox {{ data_get($protocol->additional_info, 'installation') === 'modification' ? 'checked' : '' }}"></span>
                            Modyfikacja
                        </span>

                        <span>
                            <span
                                class="checkbox {{ data_get($protocol->additional_info, 'installation') === 'existing' ? 'checked' : '' }}"></span>
                            Istniejąca
                        </span>
                    </div>
                </td>
            </tr>
        </table>

        <!-- 3. TABELA SZEROKA (Orzeczenie) -->
        <table style="width: 100%; margin-top: -1px;">
            <tr>
                <td class="judgement" style="vertical-align: top;">
                    <div class="label">Orzeczenie:</div>
                    <div class="judgement-box protocol-info"
                        style="font-size: 20px; padding-left: 30px; padding-top: 30px;">
                        {{ $protocol->additional_info['decision'] ?? '' }}
                    </div>
                </td>
            </tr>
        </table>
        @php
            $lp = 1;
        @endphp
        @foreach ($protocol->switchboards as $switchboard)
            <div class="table-parameters">
                <div>
                    TABLICA {{ $switchboard->name }}
                </div>

                <div style="text-align: center;">
                    Parametry zabezpieczeń różnicowoprądowych
                </div>
            </div>
            <table class="small-table">
                <thead>
                    <tr>
                        <th style="width: 4%">Lp.</th>
                        <th style="width: 7%">Symbol</th>
                        <th style="width: 30%">Badany punkt</th>
                        <th style="width: 12%">Wyłącznik</th>
                        <th style="width: 10%">Typ</th>
                        <th style="width: 7%">In [mA]</th>
                        <th style="width: 7%">Ia [mA]</th>
                        <th style="width: 7%">ta [mA]</th>
                        <th style="width: 7%">t [ms]</th>
                        <th style="width: 7%">Ub [V]</th>
                        <th style="width: 7%">U [V]</th>
                        <th style="width: 14%">Ocena</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($switchboard->differentialBreakers as $differentialBreaker)
                        <tr class="empty-row">
                            <td style="text-align: end">
                                {{ $lp++ }}
                            </td>
                            <td>
                            </td>
                            <td>
                                {{ $differentialBreakersData[$differentialBreaker->pivot->id]['name'] ?? '' }}
                            </td>

                            <td class="break-all">
                                {{ $differentialBreaker->name }}
                            </td>

                            <td class="break-all">
                                {{ $differentialBreakersData[$differentialBreaker->pivot->id]['type'] ?? '' }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['current'] ?? '') }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['current_measured'] ?? '') }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['time'] ?? '') }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['time_measured'] ?? '') }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['safe_voltage'] ?? '') }}
                            </td>

                            <td class="break-all" style="text-align: end;">
                                {{ (float) ($differentialBreakersData[$differentialBreaker->pivot->id]['safe_voltage_measured'] ?? '') }}
                            </td>
                            <td class="break-all grade-cell">
                                {{ $differentialBreaker->grade === true ? 'Pozytywna' : 'Negatywna' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </section>
    {{-- KOLEJNE STRONY DLA KAŻDEJ ROZDZIELNICY --}}
    @foreach ($protocol->switchboards as $switchboard)
        <section class="page avoid-break page-break">

            {{-- 1. NAGŁÓWEK TABLICY (Zwykły tekst pogrubiony nad tabelami, pojawia się raz na stronę rozdzielnicy) --}}
            <div style="font-weight: bold; font-size: 13px; margin-bottom: 2mm;">
                Tablica {{ $switchboard->name }}
            </div>


            {{-- 2. TABELA: BADANIE STANU IZOLACJI KABLI --}}
            <div class="table-title">
                Badanie stanu izolacji kabli
            </div>

            <table class="small-table">
                <thead>
                    <tr>
                        <th style="width: 4%;">Lp.</th>
                        <th style="width: 7%">Symbol</th>
                        <th style="width: 18%">Badany odcinek</th>
                        <th style="width: 10%">Izolacja</th>
                        <th style="width: 6%">s<br>[mm²]</th>
                        <th style="width: 6%">l<br>[m]</th>
                        <th style="width: 6%">t<br>[°C]</th>
                        <th style="width: 7%">Rs<br>[MΩ]</th>
                        <th style="width: 7%">Rsx<br>[MΩ]</th>
                        <th style="width: 6%">K20</th>
                        <th style="width: 6%">Ra<br>[MΩ]</th>
                        <th style="width: 10%">Ocena</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $lpIzolacja = 1;
                    @endphp
                    @foreach ($switchboard->insulationCataloges as $insulationCataloge)
                        @php
                            $pivot = $insulationCataloge->pivot;

                            $resistanceValue = $insulationCatalogesData[$pivot->id]['resistance'] ?? null;
                            $factorValue = $insulationCatalogesData[$pivot->id]['factor'] ?? null;

                            $resistance = is_numeric($resistanceValue) ? (float) $resistanceValue : null;
                            $factor = is_numeric($factorValue) ? (float) $factorValue : null;

                            $rsx = $resistance !== null && $factor !== null ? $factor * $resistance : null;

                            $allowed = is_numeric($insulationCataloge->resistance_allowed)
                                ? (float) $insulationCataloge->resistance_allowed
                                : null;

                            $grade = match (true) {
                                $rsx === null || $allowed === null => null,
                                $rsx >= $allowed => true,
                                default => false,
                            };
                        @endphp
                        <tr class="empty-row">
                            <td style="text-align: end">
                                {{ $lpIzolacja++ }}
                            </td>
                            <td>
                            </td>
                            <td>
                                {{ $insulationCatalogesData[$insulationCataloge->pivot->id]['section'] ?? '' }}
                            </td>

                            <td class="break-all">
                                {{ $insulationCataloge->insulation }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) $insulationCatalogesData[$insulationCataloge->pivot->id]['cross_section'] ?? '' }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) ($insulationCatalogesData[$insulationCataloge->pivot->id]['length'] ?? '') }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) ($insulationCatalogesData[$insulationCataloge->pivot->id]['temperature'] ?? '') }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) $insulationCatalogesData[$insulationCataloge->pivot->id]['resistance'] }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) ($insulationCatalogesData[$insulationCataloge->pivot->id]['resistance'] * $insulationCatalogesData[$insulationCataloge->pivot->id]['factor']) }}
                            </td>

                            <td style="text-align: end">
                                {{ (float) ($insulationCatalogesData[$insulationCataloge->pivot->id]['factor'] ?? '') }}
                            </td>
                            <td style="text-align: end">
                                {{ (float) $insulationCataloge->resistance_allowed }}
                            </td>
                            <td class="grade-cell">
                                <span @class([
                                    'inline-flex w-full items-center justify-center rounded-md px-2 py-1.5 text-[11px] font-semibold',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' =>
                                        $grade === true,
                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' =>
                                        $grade === false,
                                    'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300' =>
                                        $grade === null,
                                ])>
                                    {{ match ($grade) {
                                        true => 'Pozytywna',
                                        false => 'Negatywna',
                                        null => 'Brak',
                                    } }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


            {{-- 3. TABELA: SAMOCZYNNE WYŁĄCZENIE --}}
            <div class="table-title " style="padding-top: 10px; padding-left: 5px;">
                ({{  $switchboard->protocol->additional_info['network_type'] ?? '' }}) Badanie ochrony przed porażeniem przez samoczynne wyłączenie
            </div>

            <table class="small-table ">
                <thead>
                    <tr>
                        <th style="width: 4%">Lp.</th>
                        <th style="width: 7%">Symbol</th>
                        <th style="width: 30%">Badany punkt</th>
                        <th style="width: 12%">Wyłącznik</th>
                        <th style="width: 6%">Typ</th>
                        <th style="width: 7%">In [A]</th>
                        <th style="width: 7%">Ia [A]</th>
                        <th style="width: 7%">Zs [Ω]</th>
                        <th style="width: 7%">Za [Ω]</th>
                        <th style="width: 8%">Ik [A]</th>
                        <th style="width: 10%">Ocena</th>
                    </tr>
                </thead>

                <tbody>
                    @php
                        $lpItem = 1;
                    @endphp
                    @foreach ($switchboard->rooms as $room)
                        <tr>
                            <td colspan="11" class="section"
                                style="padding-top: 10px; padding-bottom: 10px; padding-left: 5px;">
                                {{ $room->name }}
                            </td>
                        </tr>
                        <tr class="description-row">
                            <td colspan="11" style="padding-bottom: 20px;">
                                U<sub>N</sub> = {{ (float) $switchboard->protocol->voltage }}, Typ sieci =
                                {{ $switchboard->protocol->additional_info['network_type'] ?? '' }}
                            </td>
                        </tr>

                        @foreach ($room->items as $item)
                            <tr class="empty-row">
                                <td style="text-align: end">
                                    {{ $lpItem++ }}
                                </td>
                                <td>
                                    {{ 'GO' . $loop->iteration }}
                                </td>
                                <td class="break-all">
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->circuitBreaker?->name }}
                                </td>
                                <td>
                                    {{ $item->circuitBreaker?->characteristic }}
                                </td>
                                <td style="text-align: end;">
                                    {{ (float) $item->circuitBreaker?->rated_current }}
                                </td>
                                <td style="text-align: end;">
                                    {{ (float) $item->circuitBreaker?->rated_current * $item->circuitBreaker?->multiplicity_factor }}
                                </td>
                                <td style="text-align: end;">
                                    {{ (float) $item?->measure_impedance }}
                                </td>
                                <td style="text-align: end;">
                                    {{ (float) $item?->calculatedImpedance }}
                                </td>
                                <td style="text-align: end;">
                                    {{ (float) $item->currentIk }}
                                </td>
                                <td class="grade-cell">
                                    @if ($item->grade === null)
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-md bg-gray-100 px-2 py-1.5 text-[11px] font-semibold text-gray-500 dark:bg-gray-700 dark:text-gray-300">Brak
                                            danych</span>
                                    @elseif ($item->grade)
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-md bg-green-100 px-2 py-1.5 text-[11px] font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">Pozytywna</span>
                                    @else
                                        <span
                                            class="inline-flex w-full items-center justify-center rounded-md bg-red-100 px-2 py-1.5 text-[11px] font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">Negatywna</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

        </section>
    @endforeach
    {{-- STRONA 3: BADANIE REZYSTANCJI IZOLACJI PRZEWODÓW (Poza pętlą rozdzielnic) --}}
    <section class="page avoid-break page-break">

        <div class="table-title mt-4">
            Badanie rezystancji izolacji przewodów
        </div>

        @php
            $columnsCount = $protocol->wireResistances?->last()?->columns_count == 10 ? 10 : 6;

            // 1. Pobieramy ID rozdzielnic i z nich od razu pokoje
            $switchboardIds = $protocol->switchboards()->pluck('id');
            $rooms = App\Models\Room::whereIn('switchboard_id', $switchboardIds)->orderBy('id')->get();
            $roomIds = $rooms->pluck('id');

            // 2. Tworzymy mapę unikalnych nazw pokojów do sortowania
            $orderedRoomNames = [];
            foreach ($rooms as $room) {
                $cName = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $room->name));
                if ($cName !== '' && !in_array($cName, $orderedRoomNames, true)) {
                    $orderedRoomNames[] = $cName;
                }
            }

            // 3. Pobieramy rekordy z bazy
            $resistances = App\Models\WireResistance::with('room')
                ->where(function ($query) use ($roomIds, $protocol) {
                    $query->where('protocol_id', $protocol->id)->orWhereIn('room_id', $roomIds);
                })
                ->get();

            // 4. Sortujemy dane identycznie jak w Livewire
            $sortedWireResistances = $resistances
                ->sort(function ($a, $b) use ($orderedRoomNames) {
                    $getName = fn($r) => (string) ($r->name ?: $r->room?->name);
                    $nameA = $getName($a);
                    $nameB = $getName($b);

                    $cleanNameA = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $nameA));
                    $cleanNameB = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $nameB));

                    $posA = array_search($cleanNameA, $orderedRoomNames, true);
                    $posA = $posA !== false ? $posA : 99999;

                    $posB = array_search($cleanNameB, $orderedRoomNames, true);
                    $posB = $posB !== false ? $posB : 99999;

                    if ($posA !== $posB) {
                        return $posA <=> $posB;
                    }

                    $aIs400 = str_contains($nameA, '400');
                    $bIs400 = str_contains($nameB, '400');

                    if ($aIs400 !== $bIs400) {
                        return $aIs400 ? 1 : -1;
                    }

                    return $a->id <=> $b->id;
                })
                ->values();
        @endphp

        <table class="small-table  avoid-break">
            <thead>
                <tr>
                    <th style="width: 4%">Lp.</th>
                    <th style="width: 7%">Symbol</th>
                    <th style="width: 28%">Nazwa obwodu</th>

                    @if ($columnsCount == 6)
                        <th>L1-L2<br>[MΩ]</th>
                        <th>L1-L3<br>[MΩ]</th>
                        <th>L2-L3<br>[MΩ]</th>
                        <th>L1-PEN<br>[MΩ]</th>
                        <th>L2-PEN<br>[MΩ]</th>
                        <th>L3-PEN<br>[MΩ]</th>
                    @elseif($columnsCount == 10)
                        <th>L1-L2<br>[MΩ]</th>
                        <th>L1-L3<br>[MΩ]</th>
                        <th>L2-L3<br>[MΩ]</th>
                        <th>L1-PE<br>[MΩ]</th>
                        <th>L2-PE<br>[MΩ]</th>
                        <th>L3-PE<br>[MΩ]</th>
                        <th>L1-N<br>[MΩ]</th>
                        <th>L2-N<br>[MΩ]</th>
                        <th>L3-N<br>[MΩ]</th>
                        <th>N-PE<br>[MΩ]</th>
                    @endif

                    <th>Ra<br>[MΩ]</th>
                    <th style="width: 10%">Ocena</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $lpWire = 1;
                @endphp
                @foreach ($sortedWireResistances as $wireResistance)
                    @php
                        $grade = $this->calculateGradeForRecord($wireResistance);
                    @endphp

                    <tr class="empty-row-large">
                        <td style="text-align: end">
                            {{ $lpWire++ }}
                        </td>
                        <td>
                            {{ 'OZ' . $loop->iteration }}
                        </td>
                        <td class="break-all">
                            @if ($wireResistance->name != null)
                                {{ $wireResistance->name }}
                            @else
                                {{ 'Obwód zas. ' . $wireResistance->room?->name }}
                            @endif
                        </td>

                        @for ($i = 1; $i <= $columnsCount; $i++)
                            <td style="text-align: end">
                                {{ $wireResistance->{'section_' . $i} !== null && $wireResistance->{'section_' . $i} !== '' ? (float) $wireResistance->{'section_' . $i} : '' }}
                            </td>
                        @endfor

                        <td style="text-align: end">
                            {{ $wireResistance->resistance }}
                        </td>
                        <td class="grade-cell">
                            <span @class([
                                'inline-flex w-full items-center justify-center rounded-md px-2 py-1.5 text-[11px] font-semibold',
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' =>
                                    $grade === true,
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' =>
                                    $grade === false,
                                'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-300' =>
                                    $grade === null,
                            ])>
                                {{ match ($grade) {
                                    true => 'Pozytywna',
                                    false => 'Negatywna',
                                    null => 'Brak',
                                } }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    {{-- certyfikaty --}}
    @for ($i = 1; $i <= 3; $i++)
        <section class="page avoid-break" style="page-break-before: always;">
            <img class="full-page-attachment" src="{{ Storage::url('attachment/c' . $i . '.png') }}"
                alt="Załącznik {{ $i }}">
        </section>
    @endfor
    <script>
        window.addEventListener('load', () => {
            window.print();
        });
    </script>
</div>
