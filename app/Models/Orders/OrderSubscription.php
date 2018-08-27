<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderSubscription extends Model
{
    protected $guarded = [];

    public function orderProduct()
    {
        return $this->belongsTo('App\Models\Orders\OrderProduct', 'order_product_id');
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Orders\Order', 'order_id');
    }
}