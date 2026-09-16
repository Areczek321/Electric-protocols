<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Protocol extends Model
{
    /** @use HasFactory<\Database\Factories\ProtocolFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'additional_info' => 'array',
        'date' => 'date',
        'next_date' => 'date',
    ];

    public function switchboards(): HasMany
    {
        return $this->hasMany(Switchboard::class);
    }
    public function circuitBreakers(): HasMany
    {
        return $this->hasMany(Switchboard::class);
    }
    
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function rooms(): HasManyThrough
    {
        return $this->hasManyThrough(Room::class,Switchboard::class);
    }
    public function wireResistances(): HasMany
    {
        return $this->hasMany(WireResistance::class);
    }
    
}
