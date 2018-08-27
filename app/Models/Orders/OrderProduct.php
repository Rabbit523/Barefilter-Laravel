<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo('App\Models\Stores\Product', 'product_id');
    }
    public function subscription()
    {
        return $this->belongsTo('App\Models\Orders\Subscription', 'subscription_id');
    }
    public function order()
    {
        return $this->belongsTo('App\Models\Orders\Order', 'order_id');
    }
}