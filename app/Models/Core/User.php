<?php

namespace App\Models\Core;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    
    use Notifiable;
    public static $adminRole = 1;
    public static $partnerRole = 2;
    public static $memberRole = 3;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'first_name', 'last_name', 'role_id', 'email', 'password', 'phone', 'shipping_id', 'billing_id', 'properties'
    ];

    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }

    public function shipping()
    {
        return $this->belongsTo('App\Models\Core\Address', 'shipping_id');
    }

    public function billing()
    {
        return $this->belongsTo('App\Models\Core\Address', 'billing_id');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Orders\Order', 'user_id');
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
