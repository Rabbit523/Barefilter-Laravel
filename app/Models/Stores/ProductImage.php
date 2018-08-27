<?php

namespace App\Models\Stores;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded = [];

    protected $appends = array('url');
    
    public function getUrlAttribute()
    {
        //return "http://barefilter.no/assets/uploads/products/" . sprintf('%s/%s', $this->product_id, $this->uri);
        return route('home') . "/assets/uploads/products/" . sprintf('%s/%s', $this->product_id, $this->uri);
    }
    
}