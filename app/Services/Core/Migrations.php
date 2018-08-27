<?php

namespace App\Services\Core;

use App\Models\Core\User;
use App\Models\Core\Address;

use App\Models\Orders\Order;
use App\Models\Orders\OrderProduct;

use App\Models\Stores\Product;
use App\Models\Stores\ProductImage;
use App\Models\Stores\ProductCategory;

use App\Services\Core\Users;
use App\Services\Core\Addresses;
use App\Services\Orders\Manager as OrdersManager;

class Migrations{


    public static function restore(){
        //self::categories();
        //self::products();
        //self::productSizes();
        //self::productImages();
        self::members();
        self::orders();
        self::orderProducts();
        OrdersManager::discoverSubscriptions();
        return true;
    }

    public static function getUrlFriendlyString($str) 
    {
        // convert spaces to '-', remove characters that are not alphanumeric
        // or a '-', combine multiple dashes (i.e., '---') into one dash '-'.
        $_str = preg_replace("/-+/", "-", preg_replace("/[^a-z0-9-]/", "",
           strtolower(str_replace(" ", "-", $str))));
        return substr($_str, 0, 40);
     }

    public static function categories()
    {
        $categories = json_decode(file_get_contents(public_path('migrations/categories.json')));
        $results = array();
        foreach($categories as $category){
            $p = ProductCategory::create([
                "id" => $category->id,
                "type_id" => $category->type,
                "parent_id" => ($category->parent_id != null) ? $category->parent_id : 0,
                "priority" => 10,
                "active" => $category->is_active,
                "handle" => self::getUrlFriendlyString($category->name),
                "name" => $category->name,
                "description" => ($category->description != null) ? $category->description : "",
                "meta_title" => ($category->meta_title != null) ? $category->meta_title : "",
                "meta_keywords" => ($category->meta_keywords != null) ? $category->meta_keywords : "",
                "meta_description" => ($category->meta_description != null) ? $category->meta_description : "",
                "image" => ($category->logo_image != null) ? $category->logo_image : ""               
            ]);
            array_push($results, $p);
        }
        return $results;
    }
    public static function products()
    {
        $products = json_decode(file_get_contents(public_path('migrations/products.json')));
        $results = array();
        foreach($products as $product){
            $p = Product::create([
                "id" => $product->id,
                "category_id" => ($product->sub_category_id !== '') ? $product->sub_category_id : $product->category_id,
                "active" => true,
                "handle" => self::getUrlFriendlyString($product->name),
                "name" => $product->name,
                "sku" => $product->sku,
                "short_description" => ($product->short_description != null) ? $product->short_description : "",
                "description" => ($product->description != null) ? $product->description : "",
                "meta_title" => ($product->meta_title != null) ? $product->meta_title : "",
                "meta_keywords" => ($product->meta_keywords != null) ? $product->meta_keywords : "",
                "meta_description" => ($product->meta_description) ? $product->meta_description : "",
                "bags" => ($product->bags != null) ? $product->bags : 0,
                "price" => ($product->price != null) ? $product->price : 0,
                "in_stock" => 10
            ]);
            array_push($results, $p);
        }
        return $results;
    }
    public static function productSizes()
    {
        $products = json_decode(file_get_contents(public_path('migrations/product_sizes.json')));
        $results = array();
        foreach($products as $product){
            $p = Product::find($product->product_id);
            if($p){
                $p->width = $product->width;
                $p->height = $product->height;
                $p->length = $product->length;
                $p->weight = $product->weight;
                $p->save();
                array_push($results, $p);
            }
            
        }
        return $results;
    }
    public static function productImages()
    {
        $images = json_decode(file_get_contents(public_path('migrations/product_images.json')));
        $results = array();
        foreach($images as $image){
            $p = ProductImage::create([
                "product_id" => $image->product_id,
                "uri" => $image->image
            ]);
            array_push($results, $p);
        }
        return $results;
    }


    public static function members()
    {
        $users = json_decode(file_get_contents(public_path('migrations/members.json')));
        $results = array();
        foreach($users as $user){
            $u = User::create([
                "id" => $user->id,
                "role_id" => User::$memberRole,
                "first_name" => ($user->firstname != null) ? $user->firstname : "",
                "last_name" => ($user->lastname != null) ? $user->lastname : "",
                "email" => $user->email,
                "password" => $user->password,
                "phone" => ($user->phone != null) ? $user->phone : "",
                "shipping_id" => 0,
                "billing_id" => 0,
                "properties" => "{}",
                "created_at" => $user->created_at,
                "updated_at" => $user->updated_at            
            ]);
            array_push($results, $u);
        }
        return $results;
    }
    public static function orders()
    {
        $orders = json_decode(file_get_contents(public_path('migrations/orders.json')));
        $results = array();
        $skipped = array();
        foreach($orders as $order){
            $user = self::getUserFromOrder($order);
            if($user){
                $order->user_id = $user->id;
                $addresses = self::getAddressesFromOrder($order, $user);
                $o = Order::create([
                    "id" => $order->id,
                    "status" => 0,
                    "user_id" => $order->user_id,
                    "shipping_method_id" => self::getShippingMethodId($order),
                    "payment_method_id" => self::getPaymentMethodId($order),
                    "transaction_id" => ($order->transaction_id != null) ? $order->transaction_id : "",
                    "consignment_id" => "",
                    "shipping_id" => $addresses["shipping"]->id,
                    "billing_id" => $addresses["billing"]->id,
                    "discount" => ($order->discount != null) ? $order->discount : 0,
                    "total" => $order->total_price,
                    "properties" => json_encode(self::getPropertiesFromOrder($order)),
                    "created_at" => $order->created_at,
                    "updated_at" => $order->updated_at            
                ]);
                $user->shipping_id = $addresses["shipping"]->id;
                $user->billing_id = $addresses["billing"]->id;
                $user->save();
                array_push($results, $o);
            }else{
                array_push($skipped, $order);
            }
        }
        return ["imported" => count($results), "skipped" => count($skipped)];
    }

    private static function getUserFromOrder($order)
    {
        return ($order->user_id === null) ?  User::where('email', $order->email)->first() : User::find($order->user_id);
    }
    private static function getAddressesFromOrder($order, $user)
    {
        $shippingData = [
            "user_id" => $order->user_id,
            "first_name" => ($order->firstname != null) ? $order->firstname : "",
            "last_name" => ($order->lastname != null) ? $order->lastname : "",
            "email" => ($order->email != null) ? $order->email : "",
            "phone" => ($order->phone != null) ? $order->phone : "",
            "street_address" => ($order->address != null) ? $order->address : "",
            "postal_code" => ($order->postal != null) ? $order->postal : "",
            "city" => ($order->zipplace != null) ? $order->zipplace : ""
        ];
        $billingData = [
            "user_id" => $order->user_id,
            "first_name" => ($order->billing_firstname != null) ? $order->billing_firstname : "",
            "last_name" => ($order->billing_lastname != null) ? $order->billing_lastname : "",
            "email" => ($order->billing_email != null) ? $order->billing_email : "",
            "phone" => ($order->billing_phone != null) ? $order->billing_phone : "",
            "street_address" => ($order->billing_address != null) ? $order->billing_address : "",
            "postal_code" => ($order->billing_postal != null) ? $order->billing_postal : "",
            "city" => ($order->billing_zipplace != null) ? $order->billing_zipplace : ""
        ];
        $shipping = Addresses::createIfNotFound($shippingData); 
        $billing = ($order->same_as !== "1") ? Addresses::createIfNotFound($billingData) : $shipping;
        $user->shipping_id = $shipping->id;
        $user->billing_id = $billing->id;
        $user->save();
        return ["shipping" => $shipping, "billing" => $billing];
    }

    private static function getShippingMethodId($order)
    {
        $id = 0;
        switch($order->shipping_by){
            case "HeltHjem My Pack":
                $id = 3;
                break;
            case "My Pack":
                $id = 3;
                break;
            case "HeltHjem  Home Delivery":
                $id = 1;
                break;
            case "Bedriftspakke":
                $id = 2;
                break;
            case "Postnord Home Delivery":
                $id = -1;
                break;
            default:
                $id = 0;
                break;
        }
        return $id;
    }

    private static function getPaymentMethodId($order)
    {
        $id = 0;
        switch($order->payment_method){
            case 1:
                $id = 2;
                break;
            case 3:
                $id = 1;
                break;
            default: // 2 - Maybe PayPal? Ask the freaking Indian.
                $id = 0;
                break;
        }
        return $id;
    }

    private static function getPropertiesFromOrder($order)
    {
        $properties = array();
        $properties["migrated"] = true;
        if($order->shipping_by == "HeltHjem My Pack" || $order->shipping_by == "My Pack"){
            $properties["tas"] = "helthjem_mypack";
            $servicePartner = [
                "number" => $order->store_id,
                "name" => $order->store_name,
                "address1" => $order->store_address,
                "city" => $order->zipplace,
                "country" => "NO",
                "postcode" => $order->postal
            ];
            $properties["service_partner"] = $servicePartner;
        }else{
            switch($order->shipping_by){
                case "HeltHjem  Home Delivery":
                    $properties["tas"] = "helthjem_home_delivery";
                    break;
                case "Bedriftspakke":
                    $properties["tas"] = "helthjem_dpd_domestic";
                    break;
                case "Postnord Home Delivery":
                    $properties["tas"] = "postnord_home_delivery";
                    break;
                default:
                    $properties["tas"] = "none";
                    break;
            }
        }
        
        $properties["netaxept"] = ($order->payment_method === 3) ? true : false;
        $properties["promocode"] = $order->discount_code;
        $properties["summary"] = [
            "goods" => 0,
            "shipping" => $order->shipping,
            "subtotal" => 0,
            "discount" => $order->discount,
            "tax" => $order->mva,
            "total" => $order->total_price
        ];
        return $properties;
    }
    public static function orderProducts()
    {
        $items = json_decode(file_get_contents(public_path('migrations/order_items.json')));
        $results = array();
        foreach($items as $item){
            $u = OrderProduct::create([
                "order_id" => $item->order_id,
                "product_id" => $item->product_id,
                "subscription_id" => (int) $item->subscription + 1,
                "amount" => $item->qty           
            ]);
            array_push($results, $u);
        }
        return $results;
    }






    public static function patchProducts($handle){
        $products = Product::where("handle", "=", $handle)->get();
        foreach($products as $index => $product){
            if($index > 0){
                $handle = $product->handle . '-' . $product->sku;
                Product::where('id', '=', $product->id)->update(["handle" => $handle]);
            }
        }
        return $products;
    }
}