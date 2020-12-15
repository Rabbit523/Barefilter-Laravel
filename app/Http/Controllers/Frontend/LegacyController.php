<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Models\Stores\ProductCategory;
use App\Models\Stores\Product;

class LegacyController extends Controller
{

    private function permanentlyMoveTo($url){
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: " . $url);
        exit();
    }

    public function sitemap() {
        $this->permanentlyMoveTo(route('sitemap'));
    }

    public function partnerLogin() {
        $this->permanentlyMoveTo(route('login'));
    }

    public function memberLogin() {
        $this->permanentlyMoveTo(route('login'));
    }

    public function becomeMember() {
        $this->permanentlyMoveTo(route('register'));
    }

    public function cart() {
        $this->permanentlyMoveTo(route('checkout'));
    }

    public function home() {
        $this->permanentlyMoveTo(route('home'));
    }

    public function residentialStore() {
        $this->permanentlyMoveTo(route('store', ["type" => "enebolig", "category" => "boventcovent"]));
    }

    public function industrialStore() {
        $this->permanentlyMoveTo(route('store', ["type" => "industribygg", "category" => "f7-industrifilter"]));
    }

    public function filters($type, $cid, $slug = null) {
        $category = ProductCategory::find($cid);
        $typeHandle = ($type === 1) ? "enebolig" : "industribygg";
        $this->permanentlyMoveTo(route('store', ["type" => $typeHandle, "category" => $category->handle]));
    }

    public function subcategory($cid, $slug = null) {
        $category = ProductCategory::find($cid);
        $typeHandle = ($category->type_id === 1) ? "enebolig" : "industribygg";
        $this->permanentlyMoveTo(route('store', ["type" => $typeHandle, "category" => $category->handle]));
    }

    public function filter($id, $slug) {
        $product = Product::find($id);
        $this->permanentlyMoveTo(route('product', ["identifier" => $product->handle]));
    }

    public function subscription() {
        $this->permanentlyMoveTo(route('subscription'));
    }

    public function about() {
        $this->permanentlyMoveTo(route('about'));
    }

    public function contact() {
        $this->permanentlyMoveTo(route('contact'));
    }

    public function customerService() {
        $this->permanentlyMoveTo(route('customer-service'));
    }

    public function payment() {
        $this->permanentlyMoveTo(route('payment'));
    }
    public function tos() {
        $this->permanentlyMoveTo(route('tos'));
    }

}
