<?php

namespace App\Models\Partners;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $guarded = [];
    
    public function orders()
    {
        return $this->hasManyThrough(
            'App\Models\Partners\BuildingOrder', 
            'App\Models\Orders\Order',
            'building_id',
            'order_id'    
        );
    }
}