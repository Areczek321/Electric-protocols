<?php

namespace App\Livewire\Protocol;

use App\Models\Protocol;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Flux;

class ProtocolPrint extends Component
{
    public $client;

    public $protocol;

    public array $differentialBreakersData = [];

    public array $insulationCatalogesData = [];

    public array $roomsData = [];

    public array $itemsData = [];

    #[Layout('layouts.print')]
    public function render()
    {
        return view('livewire.protocol.protocol-print');
    }

    public function mount(Protocol $protocol)
    {
        $this->protocol = $protocol->load('switchboards');
        $this->client = $this->protocol->client;
        $this->syncDifferentialBreakers();
        $this->syncInsulationTestings();
        $info = $this->protocol->additional_info ?? [];
        
    }

    public function syncDifferentialBreakers()
    {
        foreach ($this->protocol->switchboards as $switchboard) {
            foreach ($switchboard->differentialBreakers as $differentialBreaker) {
                $this->differentialBreakersData[$differentialBreaker->pivot->id] = [
                    'name' => $differentialBreaker->pivot->name,
                    'switchboard_id' => $differentialBreaker->pivot->switchboard_id,
                    'differential_breaker_cataloges_id' => $differentialBreaker->pivot->differential_breaker_cataloges_id,
                    'type' => $differentialBreaker->pivot->type,
                    'time_measured' => $differentialBreaker->pivot->time_measured,
                    'current_measured' => $differentialBreaker->pivot->current_measured,
                    'safe_voltage_measured' => $differentialBreaker->pivot->safe_voltage_measured,
                    'safe_voltage' => $differentialBreaker->pivot->safe_voltage,
                    'current' => $differentialBreaker->pivot->current,
                    'time' => $differentialBreaker->pivot->time,
                ];
            }
        }
    }

    public function syncInsulationTestings()
    {
        foreach ($this->protocol->switchboards as $switchboard) {
            foreach ($switchboard->insulationCataloges as $insulationCataloge) {
                $this->insulationCatalogesData[$insulationCataloge->pivot->id] = [
                    'section' => $insulationCataloge->pivot->section,
                    'switchboard_id' => $insulationCataloge->pivot->switchboard_id,
                    'insulation_catalog_id' => $insulationCataloge->pivot->insulation_catalog_id,
                    'cross_section' => $insulationCataloge->pivot->cross_section,
                    'resistance' => $insulationCataloge->pivot->resistance,
                    'temperature' => $insulationCataloge->pivot->temperature,
                    'length' => $insulationCataloge->pivot->length,
                    'factor' => $insulationCataloge->pivot->factor,
                ];
            }
        }
    }

    public function calculateGradeForRecord($record)
    {
        $hasAnyData = false;
        $requiredResistance = 1.0;
        $isNegative = false;

        // Sprawdzamy wszystkie przekroje
        for ($i = 1; $i <= 10; $i++) {
            $val = $record->{"section_{$i}"};

            if ($val !== null && $val !== '') {
                $hasAnyData = true;
                if ((float) $val < $requiredResistance) {
                    $isNegative = true;
                    break;
                }
            }
        }

        if ($hasAnyData) {
            return $isNegative ? false : true;
        }

        return null; 
    }
}
