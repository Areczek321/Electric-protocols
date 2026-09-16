<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute; // <--- POPRAWNY IMPORT
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WireResistance extends Model
{
    protected $guarded = [];

    protected $appends = ['grade']; 

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    protected function grade(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hasAnyData = false;
                $requiredResistance = $this->resistance ?? 1.0;

                for ($i = 1; $i <= 10; $i++) {
                    $val = $this->{'section_' . $i};
                    
                    if ($val !== null && $val !== '') {
                        $hasAnyData = true;
                        if ((float) $val < $requiredResistance) {
                            return 'Negatywna';
                        }
                    }
                }

                return $hasAnyData ? 'Pozytywna' : null;
            }
        );
    }
}
