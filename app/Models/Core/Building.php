<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $guarded = [];
    
    public function facilities()
    {
        return $this->hasMany('App\Models\Core\Facility', 'building_id');
    }

    public function address()
    {
        return $this->belongsTo('App\Models\Core\Address', 'address_id');
    }
}