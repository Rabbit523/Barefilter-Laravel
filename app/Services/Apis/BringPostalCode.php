<?php

namespace App\Services\Apis;

//https://developer.bring.com/api/postal-code/
class BringPostalCode extends ApiClient{

    private static $baseUrl = "https://api.bring.com/shippingguide/api/";
    
    public static function lookupPostalCode($pnr){
        $url = self::$baseUrl . "postalCode.json";
        return ($pnr != null) ? (array) json_decode(self::get($url, [
            "query" => [
                "clientUrl" => url('/'), 
                "pnr" => $pnr
            ]
        ])) : ["error" => "pnr must not be null"];
    }
}