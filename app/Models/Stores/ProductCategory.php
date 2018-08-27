<?php

namespace App\Models\Stores;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    protected $appends = array('url');
    
    public function getUrlAttribute()
    {
        return url("assets/uploads/categories") . "/" . $this->image;
    }

    public function products()
    {
        return $this->hasMany('App\Models\Stores\Product', 'category_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Stores\ProductType', 'type_id');
    }
}