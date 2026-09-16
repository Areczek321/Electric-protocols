<?php

namespace App\Livewire\Protocol;

use App\Models\CircuitBreakerCatalog;
use App\Models\Item;
use App\Models\Switchboard;
use App\Models\WireResistance;
use DB;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FormRoom extends Component
{
    public Switchboard $switchboard;

    /*
    |--------------------------------------------------------------------------
    | Formularz dodawania gniazdka
    |--------------------------------------------------------------------------
    */

    public ?int $addingToRoomId = null;

    public string $newItemName = '';

    public $availableBreakers;

    public $networkType;

    public array $selectedBreakers = [];

    public array $selectedBreakerIds = [];

    public array $roomSelectedBreakerIds = [];

    public ?int $circuitBreakerId = null;

    public $allCircuitBreakers;

    public string $deviceSymbol = '';

    public int|float|null $ratedCurrent = null;

    public int|float|null $measureImpedance = null;

    /*
    |--------------------------------------------------------------------------
    | Wartości obliczane w stanie komponentu
    |--------------------------------------------------------------------------
    |
    | Nie są zapisywane w bazie.
    |
    */

    public int|float|null $calculatedCurrent = null;

    public int|float|null $calculatedImpedance = null;

    public int|float|null $circuitBreakerMultiplier = null;

    public function mount(Switchboard $switchboard): void
    {
        $this->switchboard = $switchboard;

        $this->networkType = $this->switchboard->protocol->additional_info['network_type'] ?? null;
        $this->loadSwitchboard();
        $this->availableBreakers = $this->switchboard->circuitBreakers->sortBy('name')->unique(fn ($breaker) => $breaker->name.'|'.$breaker->characteristic.'|'.$breaker->rated_current)->values();

        $this->selectedBreakers = $this->availableBreakers->map(fn ($breaker) => ['id' => $breaker->id, 'name' => $breaker->name, 'characteristic' => $breaker->characteristic, 'rated_current' => $breaker->rated_current])->toArray();
        $this->selectedBreakerIds = $this->availableBreakers->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        $this->allCircuitBreakers = CircuitBreakerCatalog::whereNotIn('id', $this->switchboard->circuitBreakers->pluck('id'))->get();
        foreach ($this->switchboard->rooms as $room) {
            $this->roomSelectedBreakerIds[$room->id] = $room
                ->circuitBreakers
                ->pluck('id')
                ->map(fn ($id) => (string) $id)
                ->values()
                ->toArray();
        }
    }

    public function render()
    {
        return view('livewire.protocol.form-room');
    }

    /*
    |--------------------------------------------------------------------------
    | Ładowanie danych
    |--------------------------------------------------------------------------
    */

    private function loadSwitchboard(): void
    {
        $this->switchboard->load([
            'rooms.items',
            'circuitBreakers',
        ]);
    }

    private function refreshSwitchboard(): void
    {
        $this->switchboard->refresh();

        $this->loadSwitchboard();
    }

    /*
    |--------------------------------------------------------------------------
    | Pomieszczenia
    |--------------------------------------------------------------------------
    */

    public function addRoom(): void
    {
        DB::transaction(function () {
            $room = $this->switchboard->rooms()->create([
                'name' => '',
            ]);

            WireResistance::create([
                // 'protocol_id' => $this->switchboard->protocol->id,
                'room_id' => $room->id,
                'name' => null,
                'resistance' => 1,
            ]);

            $this->roomSelectedBreakerIds[$room->id] =
            $this->selectedBreakerIds;

        });
        $this->refreshSwitchboard();

    }

    public function updateRoom(
        int $roomId,
        string $field,
        mixed $newValue
    ): void {
        // Dodano 'havePower' do dozwolonych pól
        if (! in_array($field, ['name', 'voltage', 'havePower'], true)) {
            return;
        }

        $room = $this->switchboard
            ->rooms()
            ->find($roomId);

        if (! $room) {
            return;
        }

        if ($field === 'name') {
            $name = trim((string) $newValue);

            if ($name === '') {
                $this->addError(
                    "room.{$roomId}.name",
                    'Nazwa pomieszczenia nie może być pusta.'
                );

                return;
            }

            $room->update([
                'name' => $name,
            ]);
        }

        if ($field === 'voltage') {
            $voltage = filter_var(
                $newValue,
                FILTER_VALIDATE_FLOAT
            );

            if ($voltage === false || $voltage <= 0) {
                $this->addError(
                    "room.{$roomId}.voltage",
                    'Napięcie musi być większe od zera.'
                );

                return;
            }

            $room->update([
                'voltage' => $voltage,
            ]);

            if ($this->addingToRoomId === $roomId) {
                $this->calculations();

                unset($this->currentIk);
                unset($this->grade);
            }
        }

        // Dodana obsługa checkboxa Siły (400V)
        if ($field === 'havePower') {
            $room->update([
                'havePower' => filter_var($newValue, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $this->resetErrorBag("room.{$roomId}.{$field}");

        $this->refreshSwitchboard();
    }

    public function removeRoom($roomId)
    {
        $this->switchboard->rooms()->where('id', $roomId)->delete();
        $this->dispatch('show-toast', message: 'Usunięto pomieszczenie');
        Flux::toast(
            text: 'Usunięto pomieszczenie',
            heading: 'Usunięto',
            variant: 'danger'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Otwieranie formularza
    |--------------------------------------------------------------------------
    */

    public function addItem(int $roomId): void
    {
        $room = $this->switchboard->rooms()->find($roomId);

        if (! $room) {
            return;
        }
        $lastItem = $room->items->last();
        $itemName = $lastItem ? $lastItem->name : '';
        if ($lastItem) {
            $oldName = $lastItem->name;

            if (preg_match('/(\d+)$/', $oldName, $matches)) {
                $number = (int) $matches[1];
                $itemName = preg_replace('/(\d+)$/', $number + 1, $oldName);
            }
            else{
                $lastItem;
            }
        }

        Item::create([
            'room_id' => $room->id,
            'name' => $itemName,
            'measure_impedance' => null,
            'circuit_breaker_catalog_id' => null,
        ]);

        $this->refreshSwitchboard();
        $this->dispatch('show-toast', message: 'Dodano puste gniazdko');
    }

    public function cancelAddingItem(): void
    {
        $this->addingToRoomId = null;

        $this->resetItemForm();
    }

    /*
    |--------------------------------------------------------------------------
    | Wybór bezpiecznika
    |--------------------------------------------------------------------------
    */

    public function updatedCircuitBreakerId(
        int|string|null $value
    ): void {
        $this->reset([
            'deviceSymbol',
            'ratedCurrent',
            'calculatedCurrent',
            'calculatedImpedance',
            // 'multiplicityFactor',
        ]);

        $this->resetErrorBag('circuitBreakerId');

        unset($this->currentIk);
        unset($this->grade);

        if (blank($value)) {
            return;
        }

        $circuitBreaker = $this->switchboard
            ->circuitBreakers()
            ->whereKey($value)
            ->first();

        if (! $circuitBreaker) {
            $this->reset('circuitBreakerId');

            $this->addError(
                'circuitBreakerId',
                'Wybrany bezpiecznik nie należy do tej rozdzielnicy.'
            );

            return;
        }

        $this->deviceSymbol = (string) $circuitBreaker->characteristic;

        $this->ratedCurrent = $circuitBreaker->rated_current !== null
            ? (float) $circuitBreaker->rated_current
            : null;
        $this->circuitBreakerMultiplier = (float) ($circuitBreaker->multiplicity_factor ?? 0);

        $this->calculations();
    }

    /*
    |--------------------------------------------------------------------------
    | Reakcja na zmianę impedancji
    |--------------------------------------------------------------------------
    */

    public function updatedMeasureImpedance(): void
    {
        unset($this->currentIk);
        unset($this->grade);
    }

    public function updatedRatedCurrent(): void
    {
        $this->calculations();

        unset($this->grade);
    }

    /*
    |--------------------------------------------------------------------------
    | Obliczenia Ia oraz Za
    |--------------------------------------------------------------------------
    */

    public function calculations(): void
    {
        if (! $this->addingToRoomId) {
            $this->resetCalculatedValues();

            return;
        }

        $room = $this->switchboard
            ->rooms()
            ->find($this->addingToRoomId);

        if (! $room) {
            $this->resetCalculatedValues();

            return;
        }
        $ratedCurrent = (float) $this->ratedCurrent;
        $multiplier = (float) ($this->circuitBreakerMultiplier ?? 1);

        /*
         * Ia= In * k.
         *
         * Na razie mnożnik wynosi 1.
         */
        $this->calculatedCurrent = $ratedCurrent > 0
            ? round($ratedCurrent * $multiplier, 2)
            : null;

        /*
         * Za = U / Ia.
         */
        $this->calculatedImpedance =
            $this->calculatedCurrent !== null
            && $this->calculatedCurrent > 0
                ? round(
                    230 / $this->calculatedCurrent,
                    2
                )
                : null;
    }

    /*
    |--------------------------------------------------------------------------
    | Ik — computed
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function currentIk(): ?float
    {
        if (! $this->addingToRoomId) {
            return null;
        }

        $room = $this->switchboard
            ->rooms()
            ->find($this->addingToRoomId);

        if (! $room) {
            return null;
        }

        $voltage = (float) $room?->switchboard?->protocol?->voltage;

        if ($this->measureImpedance === null || $this->measureImpedance <= 0) {
            return null;
        }

        return round(
            $voltage / $this->measureImpedance,
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Ocena — computed
    |--------------------------------------------------------------------------
    |
    | Poprawne warunki:
    |
    | Ik >= Ia
    | Zs <= Za
    |
    */
    #[Computed]
    public function grade(): ?bool
    {
        $currentIk = $this->currentIk;

        if (
            $currentIk === null
            || $this->measureImpedance === null
            || $this->measureImpedance <= 0
            || $this->calculatedCurrent === null
            || $this->calculatedImpedance === null
        ) {
            return null;
        }

        return $currentIk >= (float) $this->calculatedCurrent
            && $this->measureImpedance <= (float) $this->calculatedImpedance;
    }

    /*
    |--------------------------------------------------------------------------
    | Zapis gniazdka
    |--------------------------------------------------------------------------
    */

    public function saveItem(): void
    {
        if (! $this->addingToRoomId) {
            return;
        }

        $room = $this->switchboard
            ->rooms()
            ->find($this->addingToRoomId);

        if (! $room) {
            $this->addError(
                'addingToRoomId',
                'Nie znaleziono wybranego pomieszczenia.'
            );

            return;
        }

        $validated = $this->validate([
            'newItemName' => [
                'required',
                'string',
                'max:255',
            ],
            'circuitBreakerId' => [
                'required',
                'integer',
            ],
            'measureImpedance' => [
                'required',
                'numeric',
                'gt:0',
            ],
        ]);

        $circuitBreaker = $this->switchboard
            ->circuitBreakers()
            ->whereKey($validated['circuitBreakerId'])
            ->first();

        if (! $circuitBreaker) {
            $this->addError(
                'circuitBreakerId',
                'Wybrany bezpiecznik nie jest przypisany do tej rozdzielnicy.'
            );

            return;
        }

        Item::create([
            'room_id' => $room->id,
            'name' => trim($this->newItemName),
            'measure_impedance' => $this->measureImpedance,
            'circuit_breaker_catalog_id' => $circuitBreaker->id,
        ]);

        // $this->addingToRoomId = null;

        $this->resetItemForm();

        $this->refreshSwitchboard();
        $this->dispatch('show-toast', message: 'Dodano pomiar');
    }

    /*
    |--------------------------------------------------------------------------
    | Aktualizacja istniejącego gniazdka
    |--------------------------------------------------------------------------
    */

    public function updateItem(int $itemId,string $field,mixed $value): void {
        $allowedFields = [
            'name',
            'measure_impedance',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        $item = Item::query()
            ->whereHas('room', function ($query): void {
                $query->where(
                    'switchboard_id',
                    $this->switchboard->id
                );
            })
            ->find($itemId);

        if (! $item) {
            return;
        }

        if ($field === 'name') {
            $name = trim((string) $value);

            if ($name === '') {
                return;
            }

            $item->update([
                'name' => $name,
            ]);
        }

        if ($field === 'measure_impedance') {
            if (! is_numeric($value) || $value <= 0) {
                return;
            }

            $item->update([
                'measure_impedance' => (float) $value,
            ]);
        }

        $this->refreshSwitchboard();
    }

    /*
    |--------------------------------------------------------------------------
    | Usuwanie
    |--------------------------------------------------------------------------
    */

    public function deleteItem(int $itemId): void
    {
        $item = Item::query()
            ->whereHas('room', function ($query): void {
                $query->where(
                    'switchboard_id',
                    $this->switchboard->id
                );
            })
            ->find($itemId);

        if (! $item) {
            return;
        }

        $item->delete();

        $this->refreshSwitchboard();
    }

    /*
    |--------------------------------------------------------------------------
    | Reset
    |--------------------------------------------------------------------------
    */

    private function resetItemForm(): void
    {
        $this->reset([
            'newItemName',
            'circuitBreakerId',
            'deviceSymbol',
            'ratedCurrent',
            'measureImpedance',
            'calculatedCurrent',
        ]);

        unset($this->currentIk);
        unset($this->grade);

        $this->resetValidation();
    }

    private function resetCalculatedValues(): void
    {
        $this->reset([
            'calculatedCurrent',
            'calculatedImpedance',
        ]);

        unset($this->currentIk);
        unset($this->grade);
    }

    public function patchMeasurement(int $itemId, string $field, $value): void
    {
        $allowedFields = [
            'name',
            'measure_impedance',
            'circuit_breaker_catalog_id',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        $item = Item::findOrFail($itemId);

        if ($field === 'measure_impedance') {
            $value = $value === '' ? null : (float) $value;
        }

        if ($field === 'circuit_breaker_catalog_id') {
            $value = $value === '' ? null : (int) $value;
        }

        if ($field === 'name') {
            $value = trim((string) $value);
            if ($value === '') {
                return;
            }
        }

        $item->update([
            $field => $value,
        ]);

        $this->refreshSwitchboard();
        Flux::toast(
            text: 'Zaktualizowano protokół',
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    public function saveJSON($key, $value)
    {
        $decision = $this->switchboard->protocol->additional_info ?? [];
        $decision[$key] = $value;
        $this->switchboard->protocol->additional_info = $decision;
        $this->switchboard->protocol->save();

        $friendlyNames = [
            'installation' => 'Instalacja',
            'decision' => 'Orzeczenie',
            'type_of_measurements' => 'Rodzaj pomiarów',
            'weather' => 'Pogoda',
            'network_type' => 'Typ sieci',
        ];
        $fieldName = $friendlyNames[$key] ?? $key;

        Flux::toast(
            text: 'Zapisano zmiany: '.$fieldName,
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    public function syncRoomBreakers(int $roomId): void
    {
        $room = $this->switchboard->rooms()->find($roomId);

        if (! $room) {
            return;
        }

        // Pobieramy zaznaczone ID dla danego pokoju, mapujemy na int
        $selectedIds = collect($this->roomSelectedBreakerIds[$roomId] ?? [])
            ->map(fn ($id) => (int) $id)
            ->values()
            ->toArray();

        // Zapisujemy w bazie (tabela pivot)
        $room->circuitBreakers()->sync($selectedIds);

        // Opcjonalnie: wyświetlamy powiadomienie o sukcesie
        Flux::toast(
            text: 'Zaktualizowano bezpieczniki pomieszczenia',
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    public function addCircuit($circuitBreakerId): void
    {
        if (blank($circuitBreakerId)) {
            return;
        }

        $circuitBreakerId = (int) $circuitBreakerId;

        $exists = CircuitBreakerCatalog::query()
            ->whereKey($circuitBreakerId)
            ->exists();

        if (! $exists) {
            return;
        }

        $this->switchboard
            ->circuitBreakers()
            ->syncWithoutDetaching([
                $circuitBreakerId => [
                    'slot_number' => null,
                    'slots_occupation' => 1,
                ],
            ]);

        $this->refreshSwitchboard();
        $this->availableBreakers = $this->switchboard->circuitBreakers;
        $this->allCircuitBreakers = CircuitBreakerCatalog::query()
            ->whereNotIn(
                'id',
                $this->switchboard->circuitBreakers->pluck('id')
            )
            ->orderBy('name')
            ->get();

        Flux::toast(
            text: 'Dodano bezpiecznik do rozdzielnicy',
            heading: 'Zapisano',
            variant: 'success'
        );
    }
}
