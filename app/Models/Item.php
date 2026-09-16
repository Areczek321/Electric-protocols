<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'measure_impedance',
        'circuit_breaker_catalog_id',
    ];

    protected $casts = [
        'measure_impedance' => 'float',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function circuitBreaker(): BelongsTo
    {
        return $this->belongsTo(
            CircuitBreakerCatalog::class,
            'circuit_breaker_catalog_id'
        );
    }

    /**
     * Ia
     */
    protected function calculatedCurrent(): Attribute
    {
        return Attribute::make(
            get: function (): ?float {
                $ratedCurrent = (float) ($this->circuitBreaker?->rated_current );
                $multiplier = (float) ($this->circuitBreaker?->multiplicity_factor);
                if ($ratedCurrent <= 0) {
                    return null;
                }

                return round($ratedCurrent * $multiplier, 2);
            }
        );
    }

    /**
     * Za = U / Ia
     */
    protected function calculatedImpedance(): Attribute
    {
        return Attribute::make(
            get: function (): ?float {
                $voltage = (float) ($this->room?->switchboard?->protocol?->voltage);
                $calculatedCurrent = (float) ($this->calculated_current );

                if ( $calculatedCurrent <= 0) {
                    return null;
                }

                return round(
                    230 / $calculatedCurrent,
                    2
                );
            }
        );
    }

    /**
     * Ik = U / Zs
     */
    protected function currentIk(): Attribute
    {
        return Attribute::make(
            get: function (): ?float {
                $voltage = (float) ($this->room?->switchboard?->protocol?->voltage);
                $measureImpedance = (float) $this->measure_impedance;

                if ($voltage <= 0 || $measureImpedance <= 0) {
                    return null;
                }

                return round(
                    $voltage / $measureImpedance,
                    2
                );
            }
        );
    }

    /**
     * Ik >= Ia oraz Zs <= Za
     */
    protected function grade(): Attribute
    {
        return Attribute::make(
            get: function (): ?bool {
                if (
                    $this->current_ik === null
                    || $this->calculated_current === null
                    || $this->measure_impedance === null
                    || $this->calculated_impedance === null
                ) {
                    return null;
                }

                return
                    (float) $this->current_ik >= (float) $this->calculated_current
                    && (float) $this->measure_impedance <= (float) $this->calculated_impedance;
            }
        );
    }
}