<?php

namespace App\Livewire\Protocol;

use App\Models\DifferentialBreakerCataloge;
use App\Models\InsulationCatalog;
use App\Models\Protocol;
use DB;
use Flux\Flux;
use Livewire\Component;

class Show extends Component
{
    public Protocol $protocol;

    public $nameProtocl;

    public $voltageProtocl;

    public $switchboard;

    public $dateBulidngProtocl;

    public $nextDateBulidngProtocl;

    public $addressBulidngProtocl;

    public $differentialBreakers;

    public array $differentialPivots = [];

    public function mount(Protocol $protocol)
    {
        $this->protocol = $protocol;
        // Od razu ładujemy wszystkie relacje, by uniknąć problemów z wyświetlaniem
        $this->protocol->load('switchboards.differentialBreakers');
        // $info = $this->protocol->additional_info;
        // if (!is_array($info)) {
        //     if (is_string($info) && trim($info) !== '') {
        //         $this->protocol->additional_info = ['decision' => $info];
        //     } else {
        //         $this->protocol->additional_info = [];
        //     }
        //     $this->protocol->save();
        // }
        $this->voltageProtocl = $this->protocol->voltage;
        $this->nextDateBulidngProtocl = $this->protocol->next_date?->format('Y-m-d');
        $this->addressBulidngProtocl = $this->protocol->building_address;
        $this->differentialBreakers = DifferentialBreakerCataloge::query()->orderBy('name')->get();
        $this->syncDifferentialPivots();
    }

    public function render()
    {
        return view('livewire.protocol.show');
    }

    /*
    |--------------------------------------------------------------------------
    | Obsługa Protokołu
    |--------------------------------------------------------------------------
    */
    public function updateProtocol($field = null)
    {
        $this->protocol->update([
            'voltage' => $this->voltageProtocl,
            'next_date' => $this->nextDateBulidngProtocl,
            'building_address' => $this->addressBulidngProtocl,
        ]);

        $message = 'Zapisano zmiany';

        if ($field) {
            $friendlyNames = [
                'voltage' => 'Napięcie',
                'next_date' => 'Następny pomiar',
                'building_address' => 'Adres budynku',
            ];
            $fieldName = $friendlyNames[$field] ?? $field;
            $message = "Zapisano zmiany: {$fieldName}";
        }

        Flux::toast(
            text: $message,
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    public function saveJSON($key, $value)
    {
        $decision = $this->protocol->additional_info ?? [];
        $decision[$key] = $value;
        $this->protocol->additional_info = $decision;
        $this->protocol->save();

        $friendlyNames = [
            'installation' => 'Instalacja',
            'decision' => 'Orzeczenie',
            'type_of_measurements' => 'Rodzaj pomiarów',
            'weather' => 'Pogoda',
        ];
        $fieldName = $friendlyNames[$key] ?? $key;

        Flux::toast(
            text: 'Zapisano zmiany: '.$fieldName,
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Obsługa Rozdzielnic
    |--------------------------------------------------------------------------
    */
    public function createSwitchboard()
    {
        $lastSwitchboard = $this->protocol->switchboards()->latest('id')->first();
        $newName = 'TPR 1';

        if ($lastSwitchboard && $lastSwitchboard->name) {
            if (preg_match('/(.*?)(\d+)$/', $lastSwitchboard->name, $matches)) {
                $prefix = $matches[1];
                $number = (int) $matches[2];
                $newName = $prefix.($number + 1);
            } else {
                $newName = $lastSwitchboard->name.' 1';
            }
        }

        $switchboard = $this->protocol->switchboards()->create([
            'name' => $newName,
        ]);

        $defaultCatalogId = InsulationCatalog::first()->id ?? 1;

        $sections = [
            'L1 - L2', 'L1 - L3', 'L2 - L3', 'L1 - PEN', 'L2 - PEN', 'L3 - PEN',
        ];

        $pivotData = [];
        foreach ($sections as $section) {
            $pivotData[] = [
                'switchboard_id' => $switchboard->id,
                'insulation_catalog_id' => $defaultCatalogId,
                'section' => $section,
                'temperature' => null,
                'resistance' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('insulation_catalog_switchboard')->insert($pivotData);

        // Wymuszamy przeładowanie relacji
        $this->protocol->load('switchboards.differentialBreakers');
    }

    public function updateSwitchboard($id, $newName)
    {
        $newName = trim($newName);
        if (empty($newName)) {
            return;
        }

        $switchboard = $this->protocol->switchboards()->find($id);
        if ($switchboard) {
            $switchboard->update(['name' => $newName]);
        }

        $this->protocol->load('switchboards.differentialBreakers');
    }

    public function deleteSwitchboard($switchboardId)
    {
        $switchboard = $this->protocol->switchboards()->find($switchboardId);
        $switchboard->delete();

        $this->protocol->load('switchboards.differentialBreakers');
    }

    /*
    |--------------------------------------------------------------------------
    | Obsługa Wyłączników Różnicowoprądowych (Edycja Inline)
    |--------------------------------------------------------------------------
    */
    public function addBreaker($switchboardId)
    {
        // Pobieramy ID pierwszego bezpiecznika z bazy jako domyślny
        $defaultBreakerId = $this->differentialBreakers->first()?->id;

        DB::table('differential_breaker_cataloges_switchboard')->insert([
            'switchboard_id' => $switchboardId,
            'differential_breaker_cataloges_id' => $defaultBreakerId, // Wstawiamy ID zamiast null
            'name' => 'Nowy wyłącznik',
            'current_measured' => null,
            'time_measured' => null,
            'safe_voltage_measured' => null,
            'grade' => null,
        ]);

        $this->protocol = Protocol::with('switchboards.differentialBreakers')->find($this->protocol->id);
        $this->syncDifferentialPivots();
        // Flux::toast(
        //     text: 'Dodano nowy wyłącznik',
        //     heading: 'Zapisano',
        //     variant: 'success'
        // );
    }

    public function updateDifferentialBreaker(int $pivotId, string $field, mixed $value): void
    {
        $allowedFields = [
            'differential_breaker_cataloges_id',
            'name',
            'type',
            'current',
            'time',
            'safe_voltage',
            'current_measured',
            'time_measured',
            'safe_voltage_measured',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        if (! isset($this->differentialPivots[$pivotId])) {
            return;
        }

        $numericFields = [
            'current',
            'time',
            'safe_voltage',
            'current_measured',
            'time_measured',
            'safe_voltage_measured',
        ];

        if (in_array($field, $numericFields, true)) {
            $value = $this->normalizeNumber($value);
        } elseif ($value === '') {
            $value = null;
        }

        $massUpdateFields = [
            'current',
            'time',
            'safe_voltage',
        ];

        $pivotData = $this->differentialPivots[$pivotId];
        if (! isset($pivotData['switchboard_id'])) {
            $this->syncDifferentialPivots();
            $pivotData = $this->differentialPivots[$pivotId] ?? null;
            if (! $pivotData) {
                return;
            }
        }

        if (in_array($field, $massUpdateFields, true)) {
            DB::table('differential_breaker_cataloges_switchboard')
                ->where('switchboard_id', $pivotData['switchboard_id'])
                ->where(
                    'differential_breaker_cataloges_id',
                    $pivotData['differential_breaker_cataloges_id']
                )
                ->update([
                    $field => $value,
                    'updated_at' => now(),
                ]);

            foreach ($this->differentialPivots as $id => $row) {
                $sameSwitchboard =
                    (int) $row['switchboard_id']
                    === (int) $pivotData['switchboard_id'];

                $sameBreaker =
                    (int) $row['differential_breaker_cataloges_id']
                    === (int) $pivotData['differential_breaker_cataloges_id'];

                if ($sameSwitchboard && $sameBreaker) {
                    $this->differentialPivots[$id][$field] = $value;
                }
            }

            $message = 'Zaktualizowano parametr dla podobnych wyłączników';
        } else {
            DB::table('differential_breaker_cataloges_switchboard')
                ->where('id', $pivotId)
                ->update([
                    $field => $value,
                    'updated_at' => now(),
                ]);

            $this->differentialPivots[$pivotId][$field] = $value;

            $message = 'Zaktualizowano dane wyłącznika';
        }

        Flux::toast(
            text: $message,
            heading: 'Zapisano',
            variant: 'success'
        );
    }

    public function deleteBreaker($breakerId = null)
    {
        DB::table('differential_breaker_cataloges_switchboard')
            ->where('id', $breakerId)
            ->delete();

        // Ponownie pobieramy z bazy
        $this->protocol = Protocol::with('switchboards.differentialBreakers')->find($this->protocol->id);

        Flux::toast(
            text: 'Usunięto wyłącznik z rozdzielnicy',
            heading: 'Usunięto',
            variant: 'danger'
        );
    }

    public function removeProtocol()
    {
        $this->protocol->delete();
        redirect()->route('dashboard');
    }

    public function syncDifferentialPivots(): void
    {
        $this->protocol->unsetRelation('switchboards');

        $this->protocol->load('switchboards.differentialBreakers');

        $this->differentialPivots = [];

        foreach ($this->protocol->switchboards as $switchboard) {
            foreach ($switchboard->differentialBreakers as $breaker) {
                $pivot = $breaker->pivot;

                $this->differentialPivots[$pivot->id] = [
                    'switchboard_id' => $switchboard->id,
                    'differential_breaker_cataloges_id' => $pivot->differential_breaker_cataloges_id,

                    'name' => $pivot->name,
                    'type' => $pivot->type,

                    'current' => is_null($pivot->current)
                        ? null
                        : (float) $pivot->current,

                    'time' => is_null($pivot->time)
                        ? null
                        : (float) $pivot->time,

                    'safe_voltage' => is_null($pivot->safe_voltage)
                        ? null
                        : (float) $pivot->safe_voltage,

                    'current_measured' => is_null($pivot->current_measured)
                        ? null
                        : (float) $pivot->current_measured,

                    'time_measured' => is_null($pivot->time_measured)
                        ? null
                        : (float) $pivot->time_measured,

                    'safe_voltage_measured' => is_null($pivot->safe_voltage_measured)
                        ? null
                        : (float) $pivot->safe_voltage_measured,
                ];
            }
        }
    }

    private function normalizeNumber(mixed $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = str_replace(',', '.', trim((string) $value));

        return is_numeric($value)
            ? (float) $value
                : null;
    }

}
