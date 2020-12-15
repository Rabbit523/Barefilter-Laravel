<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    // members
    public static $welcomeEmail = 0;
    public static $orderConfirmationEmail = 1;
    public static $automaticSignUpEmail = 2;
    public static $partnerAccountEmail = 3;

    // admin
    public static $newUserNotificationEmail = 5;
    public static $newOrderNotificationEmail = 6;
    public static $orderSubscriptionNotificationEmail = 7;
    




    // system
    public static $automateNetaxept = 10;
    public static $automateCargonizer = 11;
    public static $transferOrderConfirmationEmail = 12;


    // misc
    public static $contact = 20;
    public static $bookTechnicalService = 21;


    public function getPropertiesAttribute($value)
    {
        return json_decode($value);
    }
}