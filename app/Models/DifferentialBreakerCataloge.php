<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DifferentialBreakerCataloge extends Model
{
    protected $guarded = [];

    public function switchboards(): BelongsToMany
    {
        return $this->belongsToMany(
            Switchboard::class,
            'differential_breaker_cataloges_switchboard', // Jawnie podana nazwa tabeli pivot
            'differential_breaker_cataloges_id',           // Klucz obcy tego modelu w tabeli pivot
            'switchboard_id'                              // Klucz obcy docelowego modelu w tabeli pivot
        )
            ->withPivot('name', 'section', 'current_measured', 'time_measured', 'safe_voltage_measured', 'type','current','time','safe_voltage')
            ->withTimestamps();
    }


    protected function grade(): Attribute
    {
        return Attribute::make(
            get: function (): ?bool {
                if (
                    ! isset($this->pivot) ||
                    $this->pivot->current_measured === null ||
                    $this->pivot->current === null ||
                    $this->pivot->time_measured === null ||
                    $this->pivot->time === null
                ) {
                    return null;
                }

                return
                    $this->pivot->current_measured <= $this->current
                    && $this->pivot->time_measured <= $this->pivot->time;
            }
        );
    }

}
