<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InsulationCatalog extends Model
{
    protected $guarded = [];
    
    public function switchboards():  BelongsToMany
    {
        return $this->belongsToMany(Switchboard::class)->withPivot('id','factor','cross_section', 'section', 'temperature', 'resistance','length')->withTimestamps();
    }


}
