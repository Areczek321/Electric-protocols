<?php

namespace App\Livewire\Protocol;

use App\Models\CircuitBreakerCatalog;
use App\Models\Switchboard;
use Flux;
use Livewire\Component;

class CreateSwitchboard extends Component
{
    public Switchboard $switchboard;

    public array $modules = [];

    public $availableBreakers = [];

    public int $minSlotCount = 0;

    public int $slotCount = 0;

    protected $messages = [
        'modules.*.slots_occupation.required' => 'Ilość zajmowanego miejsca jest wymagane.',
        'modules.*.breaker_id.required' => 'Wybierz bezpiecznik.',
        'modules.*.slot_number.required' => 'Numer slota jest wymagany.',
    ];

    public function mount(Switchboard $switchboard)
    {
        $this->switchboard = $switchboard;
        $this->availableBreakers = CircuitBreakerCatalog::all();

        foreach ($this->switchboard->circuitBreakers as $breaker) {
            $this->modules[] = [
                'slot_number' => $breaker->pivot->slot_number,
                'slots_occupation' => $breaker->pivot->slots_occupation,
                'breaker_id' => $breaker->id,
                'characteristic' => $breaker->characteristic,
                'rated_current' => $breaker->rated_current,
                'custom_name' => $breaker->pivot->custom_name,
            ];
        }
        $this->slotCount = count($this->modules);
        $this->minSlotCount = $this->slotCount;
        if ($this->slotCount === 0) {
            $this->addSlot();
        }
        $this->recalculateSlotsFrom(0);
    }

    public function updatedModules($value, $key)
    {
        if (str_ends_with($key, '.breaker_id')) {
            $index = (int) explode('.', $key)[0];

            if (empty($value)) {
                return;
            }

            $breaker = CircuitBreakerCatalog::find($value);

            if ($breaker && $breaker->name) {
                preg_match_all('/\d/', $breaker->name, $matches);
                $digits = $matches[0] ?? [];

                $number = 0;

                if (! empty($digits)) {
                    $lastDigit = (int) end($digits);

                    if ($lastDigit === 0 && count($digits) > 1) {
                        $number = (int) $digits[count($digits) - 2];
                    } else {
                        $number = $lastDigit;
                    }
                }

                $this->modules[$index]['slots_occupation'] = $number;
                $this->recalculateSlotsFrom($index);
            }
        }

        if (str_ends_with($key, '.slot_number') || str_ends_with($key, '.slots_occupation')) {
            $index = (int) explode('.', $key)[0];

            $this->recalculateSlotsFrom($index);
        }
    }

    private function recalculateSlotsFrom(int $startIndex)
    {
        for ($i = $startIndex + 1; $i < count($this->modules); $i++) {
            $prevModule = $this->modules[$i - 1];

            $prevSlotNumber = (int) $prevModule['slot_number'];
            $prevOccupation = max(1, (int) ($prevModule['slots_occupation'] ?: 1));

            $this->modules[$i]['slot_number'] = $prevSlotNumber + $prevOccupation;
        }

        // Wymuszenie odświeżenia reaktywności tablicy w Livewire 3
        $this->modules = array_values($this->modules);
    }

    protected function saveData()
    {
        // Usuwamy 'required' dla breaker_id, jeśli ma być opcjonalne
        $this->validate([
            'modules.*.slots_occupation' => 'required|numeric',
            'modules.*.slot_number' => 'required|numeric',
            // 'modules.*.breaker_id'    => 'required', // Wyłączamy wymóg
        ]);

        $this->switchboard->circuitBreakers()->detach();

        foreach ($this->modules as $module) {
            // Jeśli breaker_id jest puste, w bazie zapisujemy null
            $breakerId = ! empty($module['breaker_id']) ? $module['breaker_id'] : null;

            $this->switchboard->circuitBreakers()->attach($breakerId, [
                'slot_number' => $module['slot_number'],
                'slots_occupation' => $module['slots_occupation'],
                'custom_name' => $module['custom_name'] ?? '',
            ]);
        }
    }

    public function save()
    {

        $this->saveData();

        Flux::toast(
            text: 'Pomyślnie dodano bezpiecznik',
            heading: 'Sukces',
            variant: 'success'
        );
    }

    public function addSlot()
    {
        $nextSlot = 1;

        if (count($this->modules) > 0) {
            $lastModule = end($this->modules);
            $lastSlot = (int) $lastModule['slot_number'];
            $lastOccupation = max(1, (int) ($lastModule['slots_occupation'] ?: 1));

            $nextSlot = $lastSlot + $lastOccupation;
        }

        $this->modules[] = [
            'slot_number' => $nextSlot,
            'breaker_id' => '',
            'slots_occupation' => 1,
        ];

        $this->slotCount = count($this->modules);
    }

    public function updatedSlotCount($value)
    {
        $targetCount = (int) $value;
        $currentCount = count($this->modules);
        if ($targetCount < $this->minSlotCount) {
            $this->slotCount = $this->minSlotCount;

            $this->addError('slotCount', "Nie możesz ustawić mniej modułów niż jest zapisane w bazie ({$this->minSlotCount}).");

            return;
        }

        $this->resetErrorBag('slotCount');

        if ($targetCount > $currentCount) {
            $nextSlot = 1;

            if ($currentCount > 0) {
                $lastModule = end($this->modules);
                $lastSlot = (int) $lastModule['slot_number'];
                $lastOccupation = max(1, (int) ($lastModule['slots_occupation'] ?: 1));

                $nextSlot = $lastSlot + $lastOccupation;
            }

            for ($i = $currentCount; $i < $targetCount; $i++) {
                $this->modules[] = [
                    'slot_number' => $nextSlot,
                    'slots_occupation' => 1,
                    'breaker_id' => '',
                ];

                $nextSlot++;
            }
        }

    }

    public function removeSlot($index)
    {
        unset($this->modules[$index]);
        $this->modules = array_values($this->modules);
        $this->slotCount = count($this->modules);
    }

    public function saveAndGo()
    {
        $this->saveData();
        redirect()->route('protocol.formRoom', ['switchboard' => $this->switchboard->id]);
    }

    public function render()
    {
        return view('livewire.protocol.create-switchboard');
    }
}
