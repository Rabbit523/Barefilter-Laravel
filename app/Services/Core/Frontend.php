<?php

namespace App\Services\Core;

use App\Models\Core\Page;


class Frontend
{
    
    public static function getAboutPage(){
        $page = Page::where("handle", "about")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Om Oss",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "about",
            "content" => json_decode($page->content)
        ];
    }

    public static function getContactPage(){
        $page = Page::where("handle", "contact")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Kontakt",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "contact",
            "content" => json_decode($page->content)
        ];
    }

    public static function getSupportPage(){
        $page = Page::where("handle", "support")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Support",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "support",
            "content" => json_decode($page->content)
        ];
    }

    public static function getPartnerPage(){
        $page = Page::where("handle", "partner")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Bli Partner",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "partner",
            "content" => json_decode($page->content)
        ];
    }

    public static function getSubscriptionPage(){
        $page = Page::where("handle", "subscription")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Filterabonnenment",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "subscription",
            "content" => json_decode($page->content)
        ];
    }

    public static function getTosPage(){
        $page = Page::where("handle", "tos")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Terms Of Service",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "tos",
            "content" => json_decode($page->content)
        ];
    }

    public static function getCustomerServicePage(){
        $page = Page::where("handle", "customer")->first();
        return [
            "title" => isset($page->title) ? $page->title : "Kundeservice",
            "description" => isset($page->description) ? $page->description : "Description",
            "page" => "customer",
            "content" => json_decode($page->content)
        ];
    }

}
