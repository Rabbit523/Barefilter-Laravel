<?php

namespace App\Services\Stores;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Stores\ProductType;
use App\Models\Stores\ProductCategory;
use App\Models\Stores\Product;
use App\Models\Stores\DiscountCode;
use App\Models\Orders\Subscription;
use App\Models\Orders\PaymentMethod;
use App\Models\Orders\ShippingMethod;

use App\Services\Orders\Statistics;

class Stores
{

    public static function search($q){
        $q = '%' . $q . '%';
        $query = Product::where('name', 'like', $q)
                ->orWhere('handle', 'like', $q)
                ->orWhere('sku', 'like', $q);
        $products = $query->with(["category.type", "images"])->get();
        return ($products) ? ["products" => $products, "subscriptions" => Subscription::where('id', '>', 0)->get()] : ["error" => "No product(s) matching " . $q];
    }


    public static function advancedSearch(Request $request){

        //checking size from search field
        $size_array = null;
        if(strpos($request->query('q'), '*') !== false)
            $size_array = explode('*', $request->query('q'));
        else if(strpos($request->query('q'), 'x') !== false)
            $size_array = explode('x', $request->query('q'));

        /// determination of size//////////
        $where['width'] = is_numeric($request->query('width')) ? $request->query('width') : (isset($size_array[0]) ? $size_array[0] : 'a');
        $where['height'] = is_numeric($request->query('height')) ? $request->query('height') : (isset($size_array[1]) ? $size_array[1] : 'a');
        $where['length'] = is_numeric($request->query('length')) ? $request->query('length') : (isset($size_array[2]) ? $size_array[2] : 'a');

        $query_row = false;
        if(is_numeric($where['width'])) {
            $query_row = Product::where([['width', '>=', $where['width'] - 4 ], ['width', '<=', $where['width'] + 4]]);
        }
        if($query_row && $query_row->get()->count())
        {
            if(is_numeric($where['height'])) {
                $query_row->where([['height', '>=', $where['height'] - 4], ['height', '<=', $where['height'] + 4]]);
            }
            if(is_numeric($where['length'])) {
                $query_row->where([['length', '>=', $where['length'] - 40], ['length', '<=', $where['length'] + 40]]);
            }
        }

        if($query_row)
        {
            $query = $query_row->where(function($q) use($request, $size_array){
                $input = ($request->query('q') !== null && !(isset($size_array[0]) && is_numeric($size_array[0]))) ? '%' . $request->query('q') . '%' : '';
                $q->where('name', 'like', $input)
                    ->orWhere('tags', 'like', $input)
                    ->orWhere('sku', 'like', $input);
            });
        }
        else
        {
            $query = Product::where(function($q) use($request, $size_array){
                $input = ($request->query('q') !== null && !(isset($size_array[0]) && is_numeric($size_array[0]))) ? '%' . $request->query('q') . '%' : '';
                $q->where('name', 'like', $input)
                    ->orWhere('tags', 'like', $input)
                    ->orWhere('sku', 'like', $input);
            });
        }
        $products = $query->with(["category", "images"])->get();
        return ($products) ? ["products" => $products, "subscriptions" => Subscription::where('id', '>', 0)->get(), "sql" => $query->toSql()] : ["error" => "No product(s) matching " . $q];

    }

    public static function searchCategories($q){
        $q = '%' . $q . '%';
        $query = ProductCategory::where('name', 'like', $q)
                ->orWhere('handle', 'like', $q);
        $categories = $query->with(["type"])->get();
        return $categories;
    }

    public static function getCategories(){
        return ProductCategory::all();
    }

    public static function getProductPreview($handle){
        $product = Product::where('handle', $handle)->with(["category", "images"])->first();
        return ($product !== null) ? $product : ["error" => "No product with handle " . $handle];
    }

    public static function getCartForCheckout($items){
        $products = Product::whereIn('id', explode(",", $items))->with(["images"])->get();
        return ($products) ? [
            "products" => $products,
            "subscriptions" => Subscription::where('id', '>', 0)->get(),
            "payment_methods" => PaymentMethod::all(),
            "shipping_methods" => ShippingMethod::all()]
        : ["error" => "No product(s) with such ids " . $handle];
    }



    public static function getDiscountForCode($code){
        $where = ["code" => $code, "active" => true];
        $discount = DiscountCode::where($where)->first();
        return ($discount) ? $discount->value : 0;
    }

    public static function getLastYearMostSoldProducts(){
        $format = "Y-m-d";
        $date = new \DateTime();
        $end = $date->format($format);
        $start = $date->modify('-12 month')->format($format);
        $products = array();
        $histogram =  Statistics::getMostSoldProducts(36, $start, $end);
        foreach($histogram as $bin){
            array_push($products, $bin->product);
        }
        return $products;
    }

}
