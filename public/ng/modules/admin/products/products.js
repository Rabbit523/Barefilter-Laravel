'use strict';
admin.controller("productsController", ["$rootScope", "$scope", "$timeout", "$sce", "barefilterAPI", function ($rootScope, $scope, $timeout, $sce, barefilterAPI) {
    $rootScope.title = "Produkter";
    var categories = [], modal = "#create-product-modal", selectedProductTagsInput = modal + " #product-tags", viewing="fields";
    var productDescription = modal + " #product-description";
    $scope.products = [];
    $scope.isAddingNew = false;
    $scope.selectedProduct = {};
    $scope.productDescription = "";
    $scope.canAddProduct = true;

    $scope.isViewing = function(test){
        return viewing === test;
    };

    $scope.view = function(target){
        viewing = target;
        if(target === 'images'){
            setTimeout(initDropZone, 100);
        }
    };

    $scope.hasProducts = function(){
        return $scope.products.length > 0;
    }

    $scope.getProductCategory = function(product){
        return (product.category.parent_id === 0) ? product.category.name : getCategory(product.category.parent_id).name;
    };

    $scope.getProductSubCategory = function(product){
        return (product.category.parent_id !== 0) ? product.category.name : "";
    };

    $scope.productCheck = function(){
        if($scope.isAddingNew){
            barefilterAPI.stores.productCheck($scope.selectedProduct.name, function(){
                $scope.canAddProduct = true;
                $scope.$apply();
            }, function(){
                $scope.canAddProduct = false;
                $scope.$apply();
            })
        }
        
    };

    $scope.addNew = function(){
        $scope.isAddingNew = true;
        $scope.selectedProduct = getDefaultProduct();
        $(productDescription).summernote('code', "");
        $(selectedProductTagsInput).tagsinput('destroy');
        $(selectedProductTagsInput).tagsinput({});
        $(modal).modal('show');
        initCategories();
    };

    $scope.edit = function(product){
        $scope.isAddingNew = false;
        $scope.selectedProduct = product;
        $scope.selectedCategoryId = (product.category.parent_id === 0) ? product.category.id : getCategory(product.category.parent_id).id;
        $scope.selectedSubCategoryId = (product.category.parent_id !== 0) ? product.category.id : 0;
        $scope.selectedTypeId = product.category.type.id.toString();
        $scope.selectedCategoryId = $scope.selectedCategoryId.toString();
        $scope.selectedSubCategoryId = $scope.selectedSubCategoryId.toString();
        $scope.productDescription = $sce.trustAsHtml(product.description);
        $scope.onCategoryChange();
        $(selectedProductTagsInput).val(product.tags);
        $(selectedProductTagsInput).tagsinput('destroy');
        $(selectedProductTagsInput).tagsinput({});
        $(productDescription).summernote('code', product.description);
        $(modal).modal('show');
    };


    $scope.save = function(){
        $(modal).modal('hide');
        alert("Produktet er opprettet");
        $scope.selectedProduct.category_id = ($scope.selectedSubCategoryId !== '0') ? parseInt($scope.selectedSubCategoryId) : parseInt($scope.selectedCategoryId);
        $scope.selectedProduct.tags = $(selectedProductTagsInput).val();
        $scope.selectedProduct.description = $(productDescription).summernote('code');
        $scope.selectedProduct.weight = 2;
        barefilterAPI.stores.createProduct($scope.selectedProduct, function(data){
            $scope.searchQuery = $scope.selectedProduct.name;
            $scope.search();
        }, function(){});
    };

    $scope.update = function(){
        $(modal).modal('hide');
        $scope.selectedProduct.category_id = ($scope.selectedSubCategoryId !== '0') ? parseInt($scope.selectedSubCategoryId) : parseInt($scope.selectedCategoryId);
        $scope.selectedProduct.tags = $(selectedProductTagsInput).val();
        $scope.selectedProduct.description = $(productDescription).summernote('code');
        var payload = JSON.parse(angular.toJson($scope.selectedProduct));
        delete payload.category;
        delete payload.images;
        barefilterAPI.stores.updateProduct(payload, function(data){
            loadProducts();
        }, function(){});
    };

    $scope.search = function(){
        barefilterAPI.stores.search($scope.searchQuery, function(data){
            $scope.products = data.products;
            $scope.$apply();
        }, function(){

        });
    };

    $scope.deleteProductImage = function(image){
        barefilterAPI.stores.deleteProductImage(image.id, function(){
            var idx = $scope.selectedProduct.images.indexOf(image);
            $scope.selectedProduct.images.splice(idx,1);
            $scope.$apply();
        }, 
        function(){});
    };

    $scope.delete = function (product) {
        if (confirm("Are you sure you want to delete this category?")) {
            barefilterAPI.stores.deleteProduct({
                pid: product.id
            }, function (data) {
                loadProducts();
            }, function () { });
        }
    };

    $scope.onTypeChange = function(){
        var selectedTypeId = parseInt($scope.selectedTypeId), selectedCategoryId;
        $scope.categories = categories.filter(function(c){ return c.parent_id === 0 && c.type_id === selectedTypeId});
        selectedCategoryId = $scope.categories[0].id;
        $scope.subcategories = categories.filter(function(c){ return c.parent_id === selectedCategoryId});
        $scope.selectedCategoryId = selectedCategoryId.toString();
        $scope.selectedSubCategoryId = $scope.subcategories.length > 0 ? $scope.subcategories[0].id.toString() : "0";
        
    }

    $scope.onCategoryChange = function(){
        var selectedCategoryId = parseInt($scope.selectedCategoryId);
        var subcategories = categories.filter(function(c){ return c.parent_id === selectedCategoryId});
        //$scope.selectedSubCategoryId =  "0";
        
        if(subcategories.length > 0 ){
            //$scope.selectedSubCategoryId = subcategories[0].id.toString();
            subcategories.unshift({id: "0", name: "Ingen"});
        }
        $scope.subcategories = subcategories;
    }

    var getSummernoteSettings = function (height) {
        return {
            height: height,
            onfocus: function (e) {
                $('body').addClass('overlay-disabled');
            },
            onblur: function (e) {
                $('body').removeClass('overlay-disabled');
            }
        }
    };

    var getDefaultProduct = function(){
        return {
            active: 1,
            sku : "",
            name: "",
            price: 0,
            description: "",
            width: 0,
            height: 0,
            length: 0
        }
    };

    var getCategory = function(id){
        var category = {name : ""};
        for(var i = 0; i < categories.length; i++){
            if(categories[i].id === id){
                category = categories[i];
                break;
            }
        }
        return category;
    };

    var onImageUploadSuccess = function (file, response) {
        alert("Produktet er opprettet");
        if (response.success) {
            if ($scope.selectedProduct.images) {
                $scope.selectedProduct.images.push(response.data);
            }
            $scope.$apply();
        }
    };

    var onSendingImage = function (file, xhr, formData) {
        formData.append("id", $scope.selectedProduct.id);
    };

    var initDropZone = function () {
        var dropzone = new Dropzone("#image-uploader", {
            url: barefilterAPI.stores.getProductImageUploadURL(),
            acceptedFiles: 'image/png, image/jpeg'
        });
        dropzone.on("sending", onSendingImage);
        dropzone.on("success", onImageUploadSuccess);
    };

    var initSummernote = function(){
        $(productDescription).summernote('destroy');
        $(productDescription).summernote(getSummernoteSettings(200));
    };

    var loadProducts = function(){
        barefilterAPI.stores.getProducts(function(paginator){
            $scope.products = paginator.data;
            $scope.$apply();
        }, function(){});
    };

    var initCategories = function(){
        var selectedTypeId = 1, selectedCategoryId;
        $scope.categories = categories.filter(function(c){ return c.parent_id === 0 && c.type_id === selectedTypeId});
        selectedCategoryId = $scope.categories[0].id;
        
        $scope.selectedCategoryId = selectedCategoryId.toString();
        $scope.selectedSubCategoryId =  "0";
        $scope.selectedTypeId = selectedTypeId.toString();

        var subcategories = categories.filter(function(c){ return c.parent_id === selectedCategoryId});
        if(subcategories.length > 0 ){
            $scope.subcategories = categories.filter(function(c){ return c.parent_id === selectedCategoryId});
            $scope.selectedSubCategoryId = subcategories[0].id.toString();
            subcategories.unshift({id: "0", name: "Ingen"});
        }
        $scope.subcategories = subcategories;
    }

    var loadCategories = function(){
        barefilterAPI.stores.getCategories(function(data){
            categories = data;
            initCategories();
            $scope.$apply();
        }, function(){});
    };



    var init = function () {
        loadCategories();
        loadProducts();

    };
    init();
}]);