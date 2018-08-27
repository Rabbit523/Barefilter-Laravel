<?php

namespace App\Services\Core;

use App\Models\Core\Address;

use Illuminate\Http\Request;

class Addresses
{
    
    public static function getMyAddresses($uid)
    {
        return Address::where('user_id', $uid)->get();
    }

    public static function create($data){
        return Address::create($data);
    }

    public static function add(Request $request){
        return Address::create([
            "user_id" => $request->input("uid"),
            "first_name" => $request->input("first_name"),
            "last_name" => $request->input("last_name"),
            "email" => $request->input("email"),
            "phone" => $request->input("phone"),
            "street_address" => $request->input("street_address"),
            "postal_code" => $request->input("postal_code"),
            "city" => $request->input("city")
        ]);
    }
    public static function delete(Request $request){
        return Address::where([
            "id" => $request->input("id")
        ])->delete();
    }

    public static function processOrderAddresses($uid, $addresses){
        $addresses["shipping"]["user_id"] = $uid;
        $shipping = (isset($addresses["shipping"]["id"])) ? Address::find($addresses["shipping"]["id"]) : Address::create($addresses["shipping"]);
        if($addresses["same"] === "true"){
            $billing = $shipping;
        }else{
            $addresses["billing"]["user_id"] = $uid;
            $billing = (isset($addresses["billing"]["id"])) ? Address::find($addresses["billing"]["id"]) : Address::create($addresses["billing"]);
        }
        return ["shipping" => $shipping, "billing" => $billing];
    }

    public static function createIfNotFound($data){
        $address = Address::where(["user_id" => $data["user_id"], "street_address" => $data["street_address"]])->first();
        if(!$address){
            $address = Address::create($data);
        }
        return $address;
    }

    public static function deleteUserAddresses($uid){
        return Address::where("user_id", $uid)->delete();
    }
}
