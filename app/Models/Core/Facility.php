<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $guarded = [];
    
    public function building()
    {
        return $this->belongsTo('App\Models\Core\Building', 'building_id');
    }

    public function orders()
    {
        return $this->hasManyThrough(
            'App\Models\Orders\Order',
            'App\Models\Partners\BuildingOrder',
            'facility_id', // Foreign key on users table...
            'id',
            'id',
            'order_id'
        );
    }
}