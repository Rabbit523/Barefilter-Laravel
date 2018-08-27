<?php

namespace App\Services\Stores;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Stores\ProductType;
use App\Models\Stores\ProductCategory;
use App\Models\Stores\Product;
use App\Models\Orders\Subscription;

use App\Models\Core\Page;

use App\Services\Stores\Stores;
use App\Services\Orders\Manager as OrdersManager;


class Frontend
{

    public static function getHomePage(){
        $page = Page::where("handle", "home")->first();
        $residentialProductType = ProductType::where('handle', 'enebolig')->first();
        $industrialProductType = ProductType::where('handle', 'industribygg')->first();
        if($residentialProductType && $industrialProductType){
            $result = [
                "title" => "",
                "page" => "Hjem",
                "content" => json_decode($page->content),
                "subscriptions" => Subscription::where('id', '>', 0)->get(),
                "residentialCategories" => ProductCategory::where(["type_id" => $residentialProductType->id, "parent_id" => 0])->orderBy('priority', 'asc')->get(),
                "industrialCategories" => ProductCategory::where(["type_id" => $industrialProductType->id, "parent_id" => 0])->orderBy('priority', 'asc')->get(),
                "products" => Stores::getLastYearMostSoldProducts()
            ];
            return $result;
        }
    }

    public static function getSearchPage(){
        $result = [
            "title" => "",
            "page" => "search",
            "subscriptions" => Subscription::where('id', '>', 0)->get(),
            "ngApp" => "barefilterSearch"
        ];
        return $result;
    }

    public static function getProductsPage($type, $category){
        $productType = ProductType::where('handle', $type)->first();
        if($productType){
            $result = [
                "title" => $productType->name,
                "page" => "store",
                "typeHandle" => $type,
                "categoryHandle" => $category,
                "subscriptions" => Subscription::where('id', '>', 0)->get(),
                "categories" => ProductCategory::where(["type_id" => $productType->id, "parent_id" => 0])->orderBy('priority', 'asc')->get(),
            ];
            $productCategory = ProductCategory::where('handle', $category)->first();
            
            if($productCategory){
                $result["title"] = $productCategory->name;
                $result["category"] = $productCategory;
                $result["products"] = Product::where('category_id', $productCategory->id)->with("images")->paginate(12);
                $result["children"] = ProductCategory::where('parent_id', $productCategory->id)->with("products.images")->get();
                foreach($result["children"] as $children) {
                    $children["count"] = ProductCategory::where('parent_id',  $children->id)->count();
                }
            }
            return $result;
        }
    }

    public static function getProductPage($handle){
        $product = Product::where('handle', $handle)->with(["category", "images"])->first();
        if($product){
            $result = [
                "title" => $product->name,
                "page" => "store",
                "subscriptions" => Subscription::where('id', '>', 0)->get(),
                "product" => $product,
                "category" => $product->category,
                "related" => Product::where("category_id", $product->category->id)->with(["category", "images"])->inRandomOrder()->take(10)->get(),
            ];
            return $result;
        }
    }

    public static function getPaymentPage(Request $request){
        $result = OrdersManager::processNetaxeptPayment($request->query("transactionId"), $request->query("responseCode"));
        $result["title"] = "Payment";
        $result["page"] = "store";
        return $result;
    }
}