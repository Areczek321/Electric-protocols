<?php

namespace App\Livewire\Protocol;

use App\Models\InsulationCatalog;
use App\Models\Protocol;
use Flux\Flux;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InsulationTestingsForm extends Component
{
    public Protocol $protocol;

    public $insulations;

    // Główna tablica przetrzymująca stan całego formularza
    public array $pivots = [];

    public function render()
    {
        return view('livewire.protocol.insulation-testings-form');
    }

    public function mount(Protocol $protocol)
    {
        $this->protocol = $protocol;
        $this->insulations = InsulationCatalog::all();
        $this->syncPivots();
    }

    public function syncPivots()
    {
        $this->protocol->unsetRelation('switchboards');
        $this->protocol->load('switchboards.insulationCataloges');
        $this->pivots = [];

        foreach ($this->protocol->switchboards as $switchboard) {
            foreach ($switchboard->insulationCataloges as $catalog) {
                $this->pivots[$catalog->pivot->id] = [
                    'switchboard_id' => $switchboard->id,
                    'section' => $catalog->pivot->section,
                    'insulation_catalog_id' => $catalog->pivot->insulation_catalog_id,
                    'cross_section' => (float) $catalog->pivot->cross_section,
                    'length' => (float) $catalog->pivot->length,
                    'temperature' => (float) $catalog->pivot->temperature,
                    'resistance' => is_null($catalog->pivot->resistance) ? null : (float) $catalog->pivot->resistance,
                    'factor' => (float) $catalog->pivot->factor ?? 1,
                ];
            }
        }
    }

    // Magiczna metoda - uruchamia się przy każdej zmianie pola podpiętego przez wire:model="pivots..."
    // public function updatedPivots($value, $key)
    // {
    //     $parts = explode('.', $key);
    //     if (count($parts) !== 2) return;

    //     [$pivotId, $field] = $parts;

    //     $value = trim((string)$value) === '' ? null : $value;
    //     if ($field === 'factor' && $value === null) {
    //         $value = 1;
    //     }

    //     // Definiujemy, które kolumny są masowe
    //     $massUpdateFields = ['cross_section', 'length', 'temperature'];

    //     if (in_array($field, $massUpdateFields)) {
    //         $switchboardId = $this->pivots[$pivotId]['switchboard_id'];
    //         DB::table('insulation_catalog_switchboard')
    //             ->where('switchboard_id', $switchboardId)
    //             ->update([$field => $value]);

    //         $message = 'Zaktualizowano pole dla wszystkich żył';
    //     } else {
    //         DB::table('insulation_catalog_switchboard')
    //             ->where('id', $pivotId)
    //             ->update([$field => $value]);

    //         $message = 'Zaktualizowano dane';
    //     }

    //     $this->syncPivots();
    //     Flux::toast(text: $message, heading: 'Zapisano', variant: 'success');
    // }

    public function temperatureResistance($resistance, $factor): ?float
    {
        if ($resistance === null || $resistance === '' || $factor === null || $factor === '') {
            return null;
        }

        return round((float) $resistance * (float) $factor, 2);
    }

    public function deleteInsulationTesting($pivotId)
    {
        DB::table('insulation_catalog_switchboard')->where('id', $pivotId)->delete();
        $this->syncPivots();
    }

    public function setWireSystem($switchboardId, $systemType)
    {
        DB::table('insulation_catalog_switchboard')->where('switchboard_id', $switchboardId)->delete();

        $sections = $systemType === '5-wire'
            ? ['L1-L2', 'L1-L3', 'L2-L3', 'L1-PE', 'L2-PE', 'L3-PE', 'L1-N', 'L2-N', 'L3-N', 'N-PE']
            : ['L1-L2', 'L1-L3', 'L2 - L3', 'L1-PEN', 'L2-PEN', 'L3-PEN'];

        $defaultCatalogId = $this->insulations->first()->id ?? null;
        $pivotData = [];

        foreach ($sections as $section) {
            $pivotData[] = [
                'switchboard_id' => $switchboardId,
                'insulation_catalog_id' => $defaultCatalogId,
                'section' => $section,
                'temperature' => null,
                'resistance' => null,
                'factor' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('insulation_catalog_switchboard')->insert($pivotData);
        $this->syncPivots();

        Flux::toast(text: 'Zmieniono układ żył', heading: 'Zaktualizowano', variant: 'success');
    }

    public function savePivotField(int $pivotId, string $field): void
    {
        if (! isset($this->pivots[$pivotId][$field])) {
            return;
        }

        $value = $this->pivots[$pivotId][$field];

        $value = trim((string) $value) === ''
            ? null
            : $value;

        if ($field === 'factor' && $value === null) {
            $value = 1;
            $this->pivots[$pivotId][$field] = 1;
        }

        $massUpdateFields = [
            'cross_section',
            'length',
            'temperature',
            'factor',
            'insulation_catalog_id'
        ];

        if (in_array($field, $massUpdateFields, true)) {
            $switchboardId = $this->pivots[$pivotId]['switchboard_id'];

            DB::table('insulation_catalog_switchboard')
                ->where('switchboard_id', $switchboardId)
                ->update([
                    $field => $value,
                    'updated_at' => now(),
                ]);

            foreach ($this->pivots as $id => $pivot) {
                if ((int) $pivot['switchboard_id'] === $switchboardId) {
                    $this->pivots[$id][$field] = $value;
                }
            }

            $message = 'Zaktualizowano pole dla wszystkich żył';
        } else {
            DB::table('insulation_catalog_switchboard')
                ->where('id', $pivotId)
                ->update([
                    $field => $value,
                    'updated_at' => now(),
                ]);

            $message = 'Zaktualizowano dane';
        }

        Flux::toast(
            text: $message,
            heading: 'Zapisano',
            variant: 'success',
        );
    }
}
