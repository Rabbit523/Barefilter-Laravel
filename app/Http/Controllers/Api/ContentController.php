<?php

namespace App\Http\Controllers\Api;

use App\Services\Core\Pages;

use Illuminate\Http\Request;

class ContentController extends ApiController{
    
    public function updatePage(Request $request){

        return $this->json(
            Pages::updatePage($request)
        );
    }

    public function getPage($handle){

        return $this->json(
            Pages::getPage($handle)
        );
    }

    public function listPages(){

        return $this->json(
            Pages::listPages()
        );
    }

}
