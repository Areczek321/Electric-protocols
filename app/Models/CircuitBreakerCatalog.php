<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CircuitBreakerCatalog extends Model
{
    public function switchboards(): BelongsToMany
    {
        return $this->belongsToMany(Switchboard::class)
            ->withPivot('slots_occupation', 'slot_number', 'id','custom_name')
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            Item::class,
            'circuit_breaker_catalog_id'
        );
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(
            Room::class,
            'circuit_breaker_catalog_room',
            'circuit_breaker_catalog_id',
            'room_id'
        )->withTimestamps();
    }
}
