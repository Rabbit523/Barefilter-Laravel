<?php

namespace App\Models\Stores;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];
    
    public function category()
    {
        return $this->belongsTo('App\Models\Stores\ProductCategory', 'category_id');
    }
    public function images()
    {
        return $this->hasMany('App\Models\Stores\ProductImage');
    }
}