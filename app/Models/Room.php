<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function switchboard(): BelongsTo
    {
        return $this->belongsTo(Switchboard::class);
    }

    public function circuitBreakers(): BelongsToMany
    {
        return $this->belongsToMany(CircuitBreakerCatalog::class, 'circuit_breaker_catalog_room', 'room_id', 'circuit_breaker_catalog_id')->withTimestamps();
    }

    public function wireResistance(): HasOne
    {
        return $this->hasOne(WireResistance::class);
    }
}
