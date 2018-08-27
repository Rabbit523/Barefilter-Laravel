<?php

namespace App\Models\Stores;

use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    public function categories()
    {
        return $this->hasMany('App\Models\Stores\ProductCategory', 'type_id');
    }
    
}