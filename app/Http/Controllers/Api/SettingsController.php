<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Services\Core\Settings;

class SettingsController extends ApiController
{

    public function get() {
        return $this->json(
            Settings::get()
        );
    }

    public function update(Request $request){
        return $this->json(
            Settings::update($request->all())
        );
    }

    
}
