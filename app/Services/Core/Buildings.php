<?php

namespace App\Services\Core;

use App\Models\Core\Building;
use App\Models\Core\Facility;
use App\Models\Core\Address;

use Illuminate\Http\Request;

class Buildings
{
    public static function profile($id)
    {
        $building = Building::where('id', $id)
            ->with(["facilities.orders.products.product.images", "address"])
            ->first();
        return $building;
    }
    public static function mine($uid)
    {
        $buildings = Building::where('user_id', $uid)
            ->with(["facilities", "address"])
            ->get();
        return $buildings;
    }

    public static function add(Request $request){
        $address = Addresses::add($request);
        return Building::create([
            "user_id" => $request->input("uid"),
            "address_id" => $address->id,
            "name" => $request->input("name"),
        ]);
    }

    public static function addFacility(Request $request){
        return Facility::create([
            "building_id" => $request->input("building_id"),
            "name" => $request->input("name"),
        ]);
    }
}
