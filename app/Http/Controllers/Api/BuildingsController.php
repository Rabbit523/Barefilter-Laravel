<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Core\Buildings;

class BuildingsController extends ApiController
{
    
    public function mine($uid) {
        return $this->json(
            Buildings::mine($uid)
        );
    }

    public function profile($id) {
        return $this->json(
            Buildings::profile($id)
        );
    }

    public function add(Request $request) {
        return $this->json(
            Buildings::add($request)
        );
    }
    public function addFacility(Request $request) {
        return $this->json(
            Buildings::addFacility($request)
        );
    }
}
