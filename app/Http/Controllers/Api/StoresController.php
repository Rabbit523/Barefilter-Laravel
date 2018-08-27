<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\Stores\Manager;
use App\Services\Stores\Stores;

class StoresController extends ApiController
{
    
    public function search($q = null) {
        return $this->json(
            Stores::search($q)
        );
    }

    public function searchCategories($q = null) {
        return $this->json(
            Stores::searchCategories($q)
        );
    }

    public function advancedSearch(Request $request) {
        return $this->json(
            Stores::advancedSearch($request)
        );
    }

    public function product($handle) {
        return $this->json(
            Stores::getProductPreview($handle)
        );
    }

    public function cart($items) {
        return $this->json(
            Stores::getCartForCheckout($items)
        );
    }

    public function discount($code) {
        return $this->json(
            Stores::getDiscountForCode($code)
        );
    }

    public function stockCheck(Request $request) {
        return $this->json(
            Manager::stockCheck($request->input('product_stock'))
        );
    }

    public function discounts(){
        return $this->json(
            Manager::getDiscounts()
        );
    }

    public function createDiscount(Request $request){
        return $this->json(
            Manager::createDiscount($request)
        );
    }

    public function updateDiscount(Request $request){
        return $this->json(
            Manager::updateDiscount($request)
        );
    }

    public function categories() {
        return $this->json(
            Manager::getCategories()
        );
    }

    public function createCategory(Request $request){
        return $this->json(
            Manager::createCategory($request)
        );
    }

    public function updateCategory(Request $request){
        return $this->json(
            Manager::updateCategory($request)
        );
    }

    public function deleteCategory(Request $request){
        return $this->json(
            Manager::deleteCategory($request)
        );
    }

    public function products() {
        return $this->json(
            Manager::getProducts()
        );
    }

    public function productCheck(Request $request) {
        return $this->json(
            Manager::productCheck($request->input('name'))
        );
    }

    public function categoryCheck(Request $request) {
        return $this->json(
            Manager::categoryCheck($request->input('name'))
        );
    }

    public function createProduct(Request $request){
        return $this->json(
            Manager::createProduct($request)
        );
    }

    public function addProductImage(Request $request){
        return $this->json(
            Manager::addProductImage($request)
        );
    }

    public function manageCategoryImage(Request $request){
        return $this->json(
            Manager::manageCategoryImage($request)
        );
    }

    public function managePartnerLogo(Request $request){
        return $this->json(
            Manager::managePartnerLogo($request)
        );
    }
    
    public function manageCategoryItemImage(Request $request){
        return $this->json(
            Manager::manageCategoryItemImage($request)
        );
    }

    public function deleteProductImage(Request $request){
        return $this->json(
            Manager::deleteProductImage($request)
        );
    }

    public function deleteProduct(Request $request){
        return $this->json(
            Manager::deleteProduct($request)
        );
    }

    public function updateProduct(Request $request){
        return $this->json(
            Manager::updateProduct($request)
        );
    }

    public function getParentID(Request $request) {
        return $this->json(
            Manager::getParentID($request)
        ); 
    }
        
    public function getSubID(Request $request) {
        return $this->json(
        Manager::getSubID($request)
        ); 
    }
        public function maincategories() {
        return $this->json(
        Manager::getMainCategories()
        );
        }
        
        public function getAllSubCategories (Request $request) {
        return $this->json(
        Manager::getAllSubCategories($request)
        );
        }
        public function manageCategoryBannerImage(Request $request){
        return $this->json(
        Manager::manageCategoryBannerImage($request)
        );
        }
        public function manageCategorySubBannerImage(Request $request){
        return $this->json(
        Manager::manageCategorySubBannerImage($request)
        );
        }
        
        public function manageCategoryCatImage(Request $request){
        return $this->json(
        Manager::manageCategoryCatImage($request)
        );
        }
    public function manageCategorySubCatImage(Request $request){
        return $this->json(
            Manager::manageCategorySubCatImage($request)
        );
    }
    public function mainTypecategories(Request $request) {
        return $this->json(
            Manager::getMainTypeCategories($request)
        );
    }
    public function manageMainCategoryBannerImage(Request $request){ 
        return $this->json(
            Manager::manageMainCategoryBannerImage($request)
        );
    }
    public function manageMainCategoryCatImage(Request $request){
        return $this->json(
            Manager::manageMainCategoryCatImage($request)
        );
    }
}
