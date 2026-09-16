<?php

namespace App\Livewire\Protocol;

use App\Models\Protocol;
use App\Models\Room;
use App\Models\WireResistance;
use Flux;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WireResistanceForm extends Component
{
    public Protocol $protocol;

    public bool $isEmbedded = false;

    public $columnCount;

    public array $resistancesData = [];

    public function render()
    {
        return view('livewire.protocol.wire-resistance-form');
    }

    public function mount(Protocol $protocol, $isEmbedded = false)
    {
        $this->protocol = $protocol;
        $this->isEmbedded = $isEmbedded;
        $this->columnCount = $this->protocol->wireResistances->last()->columns_count ?? 6;
        // $this->wireResistances =  $this->protocol->wireResistances()->with('room')->get();
        // $this->generateMissingRows();
        // $this->loadWireResistances();
        $this->syncResistances();
    }

#[Computed]
    public function wireResistances()
    {
        $switchboardIds = $this->protocol->switchboards()->pluck('id');
        
        $rooms = Room::whereIn('switchboard_id', $switchboardIds)->orderBy('id')->get();
        $roomIds = $rooms->pluck('id');

        $resistances = WireResistance::with('room')
            ->where(function ($query) use ($roomIds) {
                $query->where('protocol_id', $this->protocol->id)
                    ->orWhereIn('room_id', $roomIds);
            })
            ->get();

        // 1. Tworzymy mapę unikalnych nazw pokojów w kolejności ich PIERWSZEGO pojawienia się
        $orderedRoomNames = [];
        foreach ($rooms as $room) {
            $cName = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $room->name));
            if ($cName !== '' && !in_array($cName, $orderedRoomNames, true)) {
                $orderedRoomNames[] = $cName;
            }
        }

        return $resistances->sort(function ($a, $b) use ($orderedRoomNames) {
            
            $getName = fn($r) => (string) ($r->name ?: $r->room?->name);
            $nameA = $getName($a);
            $nameB = $getName($b);

            $cleanNameA = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $nameA));
            $cleanNameB = trim(str_replace(['Obwód zas.', '400V', '400 V'], '', $nameB));

            $posA = array_search($cleanNameA, $orderedRoomNames, true);
            $posA = $posA !== false ? $posA : 99999; // 99999 zrzuca ręcznie dodane na dół

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

        })->values(); 
    }

    public function syncResistances()
    {
        $resistances = $this->wireResistances;

        foreach ($resistances as $resistance) {

            // 1. Zabezpieczamy się przed spacjami i pustymi stringami w bazie
            $customName = trim((string) $resistance->name);

            if ($customName === '') {
                if ($resistance->room) {
                    if ($resistance->room->havePower) {
                        $name = 'Obwód zas. '.$resistance->room->name.' 400 V';
                    } else {
                        $name = 'Obwód zas. '.$resistance->room->name;
                    }
                } else {
                    $name = '';
                }
            } else {
                $name = $customName;
            }

            $this->resistancesData[$resistance->id] = [
                'name' => $name,
                'section_1' => $resistance->section_1,
                'section_2' => $resistance->section_2,
                'section_3' => $resistance->section_3,
                'section_4' => $resistance->section_4,
                'section_5' => $resistance->section_5,
                'section_6' => $resistance->section_6,
                'section_7' => $resistance->section_7,
                'section_8' => $resistance->section_8,
                'section_9' => $resistance->section_9,
                'section_10' => $resistance->section_10,
            ];
        }
    }

    public function updateResistance($id, $field, $value): void
    {
        $id = (int) $id;

        $allowedFields = [
            'name', 'section_1', 'section_2', 'section_3', 'section_4',
            'section_5', 'section_6', 'section_7', 'section_8', 'section_9', 'section_10',
            'resistance',
        ];

        if (! in_array($field, $allowedFields, true)) {
            return;
        }

        $record = WireResistance::find($id);

        if ($record) {
            if ($field === 'name') {
                if ($record->room_id) {
                    if ($record->room) {
                        $record->room->update(['name' => $value]);
                    }
                    $record->update(['name' => null]);

                    // AKTUALIZACJA STANU WIDOKU
                    $this->resistancesData[$id]['name'] = $value;
                } else {
                    $valueToSave = trim((string) $value) === '' ? null : $value;
                    $record->update(['name' => $valueToSave]);

                    // AKTUALIZACJA STANU WIDOKU
                    $this->resistancesData[$id]['name'] = $valueToSave;
                }
            } else {
                $numericFields = [
                    'section_1', 'section_2', 'section_3', 'section_4', 'section_5',
                    'section_6', 'section_7', 'section_8', 'section_9', 'section_10',
                    'resistance',
                ];

                if (in_array($field, $numericFields, true)) {
                    if (method_exists($this, 'normalizeNumber')) {
                        $value = $this->normalizeNumber($value);
                    } else {
                        $value = str_replace(',', '.', $value);
                        $value = is_numeric($value) ? (float) $value : null;
                    }
                } elseif (trim((string) $value) === '') {
                    $value = null;
                }

                $record->update([$field => $value]);

                // AKTUALIZACJA STANU WIDOKU
                $this->resistancesData[$id][$field] = $value;

                $this->calculateGradeForRecord($record);
            }

            Flux::toast(
                text: 'Zaktualizowano dane',
                heading: 'Zapisano',
                variant: 'success'
            );
        }
    }

    private function loadWireResistances()
    {
        $switchboardIds = $this->protocol->switchboards()->pluck('id');
        $rooms = Room::whereIn('switchboard_id', $switchboardIds)->orderBy('id')->get();

        $resistances = $this->protocol->wireResistances()->get();

        $this->wireResistances = $resistances->sort(function ($a, $b) use ($rooms) {
            $getRoomName = fn ($fullName) => str_replace(['Obwód zas. ', ' 400V'], '', $fullName);

            $nameA = $getRoomName($a->name);
            $nameB = $getRoomName($b->name);

            $posA = $rooms->search(fn ($r) => $r->name === $nameA);
            $posB = $rooms->search(fn ($r) => $r->name === $nameB);

            $posA = $posA === false ? 9999 : $posA;
            $posB = $posB === false ? 9999 : $posB;

            if ($posA !== $posB) {
                return $posA <=> $posB;
            }

            return strlen($a->name) <=> strlen($b->name);
        });
    }

    // public function updateResistance($id, $field, $value)
    // {
    //     $record = WireResistance::find($id);

    //     if ($record) {
    //         $allowedFields = [
    //             'name', 'section_1', 'section_2', 'section_3', 'section_4',
    //             'section_5', 'section_6', 'section_7', 'section_8', 'section_9', 'section_10',
    //         ];

    //         if (in_array($field, $allowedFields)) {
    //             $valueToSave = trim($value) === '' ? null : $value;
    //             if ($field == 'name' && $valueToSave == '') {
    //                 $valueToSave = '';
    //             }
    //             $record->update([$field => $valueToSave]);

    //             $this->calculateGradeForRecord($record);

    //             // $this->loadWireResistances();

    //         }
    //     }
    // }

    private function calculateGradeForRecord(WireResistance $record)
    {
        $hasAnyData = false;
        $requiredResistance = 1.0;
        $isNegative = false;

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
            $grade = $isNegative ? 'Negatywna' : 'Pozytywna';
        } else {
            $grade = null;
        }

    }

    public function addEmptyRow()
    {
        WireResistance::create([
            'protocol_id' => $this->protocol->id,
            'name' => 'Obwód zas.',
            'resistance' => 1,
        ]);

        unset($this->wireResistances);
        $this->syncResistances();

        Flux::toast(
            text: 'Dodano nowy wiersz',
            heading: 'Sukces',
            variant: 'success'
        );
    }

    public function updatedColumnCount($value)
    {
        // Aktualizujemy rekord protokołu w bazie danych
        $this->protocol->wireResistances()->update([
            'columns_count' => $value,
        ]);
        unset($this->wireResistances);
        $this->syncResistances();

        Flux::toast(
            text: 'Zapisano widok tabeli',
            heading: 'Sukces',
            variant: 'success'
        );
    }

    public function deleteRow($id)
    {
        $record = WireResistance::find($id);

        if ($record) {
            $record->delete();

            unset($this->resistancesData[$id]);
            unset($this->wireResistances);

            Flux::toast(
                text: 'Usunięto obwód',
                heading: 'Sukces',
                variant: 'success'
            );
        }
    }
}
