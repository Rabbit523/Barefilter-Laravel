<?php

namespace App\Services\Core;

use App\Models\Core\Page;

use Illuminate\Http\Request;


class Pages{

    public static function updatePage(Request $request){
        $data = $request->all();
        return Page::where('id', $data['id'])->update($data);
    }

    public static function getPage($handle){
        $pages = Page::where('handle', $handle)->first();
        return ($pages !== null) ? $pages : ["error" => "No page with handle " . $handle];
    }

    public static function listPages(){
        return Page::select(["id", "name", "handle", "updated_at", "created_at"])->get();
    }
}

