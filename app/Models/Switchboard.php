<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Switchboard extends Model
{
    /** @use HasFactory<SwitchboardFactory> */
    use HasFactory;

    protected $guarded = [];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class);
    }

    public function circuitBreakers(): BelongsToMany
    {
        return $this->belongsToMany(CircuitBreakerCatalog::class)
            ->withPivot('slots_occupation', 'slot_number','custom_name')
            ->withTimestamps();
    }

    public function differentialBreakers(): BelongsToMany
    {
        return $this->belongsToMany(
            DifferentialBreakerCataloge::class,
            'differential_breaker_cataloges_switchboard', // Jawnie podana nazwa tabeli pivot
            'switchboard_id',                             // Klucz obcy tego modelu w tabeli pivot
            'differential_breaker_cataloges_id'            // Klucz obcy docelowego modelu w tabeli pivot
        )
            ->withPivot('id','name', 'section','current_measured', 'time_measured', 'safe_voltage_measured', 'type','current','time','safe_voltage')
            ->withTimestamps();
    }
    public function insulationCataloges(): BelongsToMany
    {
        return $this->belongsToMany(
            InsulationCatalog::class)
            ->withPivot('id','cross_section','factor','section','resistance','temperature','length')
            ->withTimestamps();
    }
}
