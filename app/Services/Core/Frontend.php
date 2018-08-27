<?php

namespace App\Services\Core;

use App\Models\Core\Page;


class Frontend
{
    
    public static function getAboutPage(){
        $page = Page::where("handle", "about")->first();
        return [
            "title" => "Om Oss",
            "page" => "about",
            "content" => json_decode($page->content)
        ];
    }

    public static function getContactPage(){
        $page = Page::where("handle", "contact")->first();
        return [
            "title" => "Kontakt",
            "page" => "contact",
            "content" => json_decode($page->content)
        ];
    }

    public static function getSupportPage(){
        $page = Page::where("handle", "support")->first();
        return [
            "title" => "Support",
            "page" => "support",
            "content" => json_decode($page->content)
        ];
    }

    public static function getPartnerPage(){
        $page = Page::where("handle", "partner")->first();
        return [
            "title" => "Bli Partner",
            "page" => "partner",
            "content" => json_decode($page->content)
        ];
    }

    public static function getSubscriptionPage(){
        $page = Page::where("handle", "subscription")->first();
        return [
            "title" => "Filterabonnenment",
            "page" => "subscription",
            "content" => json_decode($page->content)
        ];
    }

    public static function getTosPage(){
        $page = Page::where("handle", "tos")->first();
        return [
            "title" => "Terms Of Service",
            "page" => "tos",
            "content" => json_decode($page->content)
        ];
    }

    public static function getCustomerServicePage(){
        $page = Page::where("handle", "customer")->first();
        return [
            "title" => "Kundeservice",
            "page" => "customer",
            "content" => json_decode($page->content)
        ];
    }

}
