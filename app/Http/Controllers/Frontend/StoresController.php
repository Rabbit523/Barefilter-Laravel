<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Stores\Frontend as Stores;

use Illuminate\Http\Request;

class StoresController extends Controller
{

    public function index($type = null, $category = null, $page = 0) {
        if($type === null || $category === null){
            return redirect()->route('store', ["type" => "industribygg", "category" => "f7-industrifilter"]);
        }
        return view('store.filters', Stores::getProductsPage($type, $category, $page));
    }

    public function product($handle = null) {
        return view('store.product', Stores::getProductPage($handle));
    }

    public function cart() {
        return view('store.cart', ["page" => "cart", "ngApp" => "barefilterCheckout"]);
    }

    public function payment(Request $request) {
        return view('store.payment', Stores::getPaymentPage($request));
    }
    
}
