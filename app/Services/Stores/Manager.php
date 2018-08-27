<?php

namespace App\Services\Stores;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Stores\ProductCategory;
use App\Models\Stores\Product;
use App\Models\Stores\ProductImage;
use App\Models\Stores\DiscountCode;
use App\Models\Core\User;
use App\Services\Core\Migrations;

class Manager
{
    public static function getCategories(){
        return ProductCategory::all();
    }

    public static function getMainCategories(){
        return ProductCategory::where('parent_id', '0')->get();
    }
    //changed
    public static function getMainTypeCategories(Request $request){
        //return $request->all();
        return ProductCategory::where('parent_id', $request)->get();
    }
    //changed
    public static function getAllSubCategories (Request $request) {
        $data = $request->all();
        $category = ProductCategory::where('id', $data['parent_id'])->first();
        $parent_category = ProductCategory::where('id', $category['parent_id'])->first();
        return ProductCategory::where('parent_id', $parent_category['id'])->get();
    }

    public static function categoryCheck($name){
        $handle = Migrations::getUrlFriendlyString($name);
        $category = ProductCategory::where('handle', $handle)->first();
        return ($category) ? ["error" => "existing category"] : true;
    }

    public static function productCheck($name){
        $handle = Migrations::getUrlFriendlyString($name);
        $product = Product::where('handle', $handle)->first();
        return ($product) ? ["error" => "existing product"] : true;
    }

    public static function stockCheck($product_stock){
        $product = Product::find($product_stock['product_id']);
        $product -> is_Stock = $product_stock['isStock'];
        $product -> save();
        return ["success" => "No error!"];    
    }
    //changed
    public static function createCategory(Request $request){
        $data = $request->all();        
        if ($data['type'] == 'create') {               
            $data['data']['handle'] = Migrations::getUrlFriendlyString($data['data']['name']);
            $data['data']['active'] = true;
            $data['data']['priority'] = 10;
            return ProductCategory::create($data['data']);
        } else if ($data['type'] == 'update') {
            $category = ProductCategory::where('id', $data['data']['id'])->first();
            $category['handle'] = Migrations::getUrlFriendlyString($data['data']['name']);
            $category['name'] = $data['data']['name'];
            $category['description'] = $data['data']['description'];
            $category['parent_id'] = $data['data']['parent_id'];
            $category->save();
            return $category;
        }
    }

    public static function getParentID(Request $request) {
        $data = $request->all();       
        $category = ProductCategory::where('id', $data['parent_id'])->first();       
        $sub_data = null; $sub_sub_data = null;
        if ($category->parent_id == '0') {
            $sub_data = $data;
        } 
        else {
            $parent_category = ProductCategory::where('id', $category['parent_id'])->first();
            if ($parent_category->parent_id == '0') {
                $sub_data = $category;
                $sub_sub_data = $data;
            } 
            else {
                $parent_parent_category = ProductCategory::where('id', $parent_category['parent_id'])->first();
                if ($parent_parent_category->parent_id == '0') {
                    $sub_data = $parent_category;  
                    $sub_sub_data = $category;
                } else {
                    $parent_parent_parent_category = ProductCategory::where('id', $parent_parent_category['parent_id'])->first();
                    if ($parent_parent_parent_category->parent_id == '0') {
                        $sub_data = $parent_parent_category;
                        $sub_sub_data = $parent_category;
                    }
                }
            }
        }
        $sub = array('type' => 'sub', 'data' => $sub_data);
        $sub_sub = array('type' => 'sub_sub', 'data' => $sub_sub_data);      
        $cat = array('sub' => $sub,'sub_sub' => $sub_sub
        );
        return $cat;
    }

    public static function getSubID(Request $request) {
        $data = $request->all();
        $first_sub =null;
        $second_sub =null;

        $category = ProductCategory::where('parent_id', $data['id'])->first();
        if ($category['name'] == '') {
            return ['Success' => 'Nothing sub Category!'];
        } else {
            $first_sub = $category; 
            $sub_category = ProductCategory::where('parent_id', $category['id'])->first();
            if ($sub_category['name'] == '') {
                $second_sub = $sub_category;
            } else {

            }
        }
        $cat = array(
            'sub' => $first_sub,
            'sub_sub' => $second_sub
        );
        return $cat;
    }
    
    public static function updateCategory(Request $request){
        $data = $request->all();
        if (ProductCategory::where('id', $data['id'])->first() != "null"){
            $category = ProductCategory::where('id', $data['id'])->first();
            $category['name'] = $data['name'];
            $category['description'] = $data['description'];
            $category->save();
            return $category;
        } else {
            $data["handle"] = Migrations::getUrlFriendlyString($data["name"]);
            $data["active"] = true;
            $data["priority"] = 10;
            $category = ProductCategory::create($data);              
            return $category; 
        }        
    }

    public static function deleteCategory(Request $request){
        $data = $request->all();
        return ProductCategory::where('id', $data['cid'])->delete(); 
    }

    public static function getProducts(){
        return Product::with(['category.type', 'images'])->paginate(20);
    }
        
    public static function createProduct(Request $request){
        $data = $request->all();
        $data["handle"] = Migrations::getUrlFriendlyString($data["name"]);
        return Product::create($data);
    }

    public static function addProductImage(Request $request){
        if ($request->file('file')->isValid()){
            $path = $request->file->store($request->input('id'), 'products');
            $arr = explode("/", $path);
            $productImage = ProductImage::create([
                "product_id" => $request->input('id'),
                "uri" => $arr[1]
            ]);
            return $productImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageCategoryBannerImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->banner_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryBannerImage = array(
                "label" => "mainBanner",
                "url" => $category->banner_img
            );
            return $categoryBannerImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageCategoryCatImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->cat_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryCatImage = array(
                "label" => "mainCat",
                "url" => $category->cat_img
            );
            return $categoryCatImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageCategorySubBannerImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->banner_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryBannerImage = array(
                "label" => "subBanner",
                "url" => $category->banner_img
            );
            return $categoryBannerImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageCategorySubCatImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->cat_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryCatImage = array(
                "label" => "subCat",
                "url" => $category->cat_img
            );
            return $categoryCatImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageMainCategoryBannerImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->banner_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryBannerImage = array(
                "label" => "mainCategoryBanner",
                "url" => $category->banner_img
            );
            return $categoryBannerImage;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function manageMainCategoryCatImage(Request $request){
        if ($request->file('file')->isValid()){ 
            $category = ProductCategory::where('name', $request->input('name'))->first();
            $category->cat_img = url("/assets/uploads/categories") ."/" . $request->file->store('', 'categories');
            $category->save();
            $categoryCatImage = array(
                "label" => "mainCategoryCat",
                "url" => $category->cat_img
            );
            return $categoryCatImage;
        }else{
            return ["error" => "No image attached"];
        }
    }
    public static function managePartnerLogo(Request $request){
        if ($request->file('file')->isValid()){
            $partner = User::find($request->input('id'));
            $partner ->partnerlogo = url("/assets/uploads/partners") ."/" . $request->file->store('', 'partners');
            $partner -> save();
            return $partner->partnerlogo;
        }else{
            return ["error" => "No image attached"];
        }
    }

    public static function deleteProductImage(Request $request){
        $data = $request->all();
        $productImage = ProductImage::where('id', $data['id'])->first();
        Storage::disk('products')->delete($productImage->product_id . '/' . $productImage->uri);
        return $productImage->delete();
    }

    public static function deleteProduct(Request $request){
        $data = $request->all();
        return Product::where('id', $data['id']['pid'])->delete(); 
    }
        
    public static function updateProduct(Request $request){
        $data = $request->all();
        return Product::where('id', $data['id'])->update($data);                
    }

    public static function getDiscounts(){
        return DiscountCode::all();
    }
        
    public static function createDiscount(Request $request){
        $data = $request->all();
        return DiscountCode::create($data);
    }
        
    public static function updateDiscount(Request $request){
        $data = $request->all();
        return DiscountCode::where('id', $data['id'])->update($data); 
    }
    
}
