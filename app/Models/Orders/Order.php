<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public static $paymentFailed = -1;
    public static $unprocessed = 0;
    public static $unpaid = 1;
    public static $paid = 2;
    public static $readyForShipping = 3;
    public static $shippingError = 4;
    
    protected $appends = array('identifier');
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'status', 'user_id', 'shipping_method_id', 'payment_method_id', 'transaction_id', 'consignment_id',  'shipping_id', 'billing_id', 'discount', 'total', 'properties', 'created_at', 'updated_at'
    ];
    
    public function getIdentifierAttribute()
    {
        //return sprintf('BF-%06d', $this->id);
        return $this->id;
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\Core\User', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Orders\OrderProduct');
    }

    public function shipping()
    {
        return $this->belongsTo('App\Models\Core\Address', 'shipping_id');
    }

    public function billing()
    {
        return $this->belongsTo('App\Models\Core\Address', 'billing_id');
    }

    public function subscriptions() 
    {
        return $this->hasMany('App\Models\Orders\OrderSubscription');
    }
    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }
}
