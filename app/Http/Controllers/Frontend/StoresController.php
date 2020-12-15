<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Stores\Frontend as Stores;
use Illuminate\Http\Request;

class StoresController extends Controller
{

    public function index($type = null, $category = null, $page = 0)
    {
        $categories = [
            "flexit",
            "villavent-systemair",
            "heru",
            "exvent",
            "nilan",
            "enervent",
            "ensy",
            "rego-komfovent",
            "vallox",
            "salda",
            "swegon-casa-ilto",
            "nibe",
            "dantherm",
        ];
        $_categories = [
            "filtertil-flexit-uni-serien",
            "filter-til-flexit-uni-serien",
        ];
        if ($type === null || $category === null) {
            return redirect()->route('store', ["type" => "industribygg", "category" => "f7-industrifilter"]);
        }
        if (in_array($category, $categories)) {
            header("HTTP/1.1 301 Moved Permanently");
            $url = route('store', ["type" => "enebolig", "category" => "filter-til-" . $category]);
            header("Location: " . $url);
            exit();
        }
        if (in_array($category, $_categories)) {
            header("HTTP/1.1 301 Moved Permanently");
            $url = route('store', ["type" => "enebolig", "category" => "uni-serien"]);
            header("Location: " . $url);
            exit();
        }

        return view('store.filters', Stores::getProductsPage($type, $category, $page));
    }

    public function product($handle = null)
    {
        return view('store.product', Stores::getProductPage($handle));
    }

    public function cart()
    {
        return view('store.cart', ["page" => "cart", "ngApp" => "barefilterCheckout"]);
    }

    public function payment(Request $request)
    {
        return view('store.payment', Stores::getPaymentPage($request));
    }

}
