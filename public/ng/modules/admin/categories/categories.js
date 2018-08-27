'use strict';
admin.controller("categoriesController", ["$rootScope", "$scope", 'barefilterAPI', function ($rootScope, $scope, barefilterAPI) {
    var modal = "#create-category-modal";
    var categoryDescription = modal + " #category-description";
    var viewing="fields";
    $rootScope.title = "Kategorier";
    $scope.searchQuery = "";
    $scope.categories = [];
    $scope.allCategories = [];
    $scope.loading = false;
    $scope.canAddCategory = true;
    

    $scope.isViewing = function(test){
        return viewing === test;
    };

    $scope.view = function(target){
        viewing = target;
        if(target === 'images'){
            setTimeout(initDropZone, 100);
        }
    };
    
    $scope.triggerSearch = function(){
        search();
    };

    $scope.hasCategories = function(){
        return $scope.categories.length > 0;
    };

    $scope.delete = function (category) {
        if (confirm("Are you sure you want to delete this category?")) {
            barefilterAPI.stores.deleteCategory({ cid: category.id }, function (data) {
                getCategories();
                search();
            }, function () { });
        }
    };

    $scope.categoryCheck = function(){
        if($scope.isAddingNew){
            barefilterAPI.stores.categoryCheck($scope.category.name, function(){
                $scope.canAddCategory = true;
                $scope.$apply();
            }, function(){
                $scope.canAddCategory = false;
                $scope.$apply();
            })
        }
        
    };

    $scope.addNew = function(){
        $scope.isAddingNew = true;
        $scope.selectedType = "1";
        $scope.category = getDefaultCategory();
        $(categoryDescription).summernote('code', "");
        $(modal).modal('show');
    };

    $scope.edit = function(category){
        $scope.isAddingNew = false;
        $scope.category = category;
        $scope.category.parent_id = $scope.category.parent_id.toString();
        $scope.selectedType = category.type_id.toString();
        $(categoryDescription).summernote('code', category.description);
        $(modal).modal('show');
    };

    $scope.save = function(){
        var payload = angular.copy($scope.category);
        payload.description =  $(categoryDescription).summernote('code');
        barefilterAPI.stores.createCategory(payload, function(data){
            $(modal).modal('hide');
            $scope.searchQuery = data.name;
            getCategories();
            search();
        }, function(){});
    };

    $scope.update = function(){
        var payload = angular.copy($scope.category);
        payload.description =  $(categoryDescription).summernote('code');
        delete payload.type;
        delete payload.url;
        barefilterAPI.stores.updateCategory(payload, function(data){
            $(modal).modal('hide');
        }, function(){});
    };

    var update = function(){
        
    };


    var getDefaultCategory = function(){
        return {
            name: "",
            type_id : "1",
            parent_id: "0",
            description: "",
            image: "-"
        }
    };

    var onImageUploadSuccess = function (file, response) {
        if (response.success) {
            $scope.category.image = response.data;
            $scope.$apply();
            alert("Kategori er opprettet");
        }
    };

    var onSendingImage = function (file, xhr, formData) {
        formData.append("id", $scope.category.id);
    };

    var initDropZone = function () {
        var dropzone = new Dropzone("#image-uploader", {
            url: barefilterAPI.stores.getCategoryImageUploadURL(),
            acceptedFiles: 'image/png, image/jpeg'
        });
        dropzone.on("sending", onSendingImage);
        dropzone.on("success", onImageUploadSuccess);
    };

    var search = function(){
        $scope.loading = true;
        barefilterAPI.stores.searchCategories($scope.searchQuery, function(data){
            $scope.categories = data;
            $scope.loading = false;
            $scope.$apply();
        }, function(){

        });
    };
    var getCategories = function(){
        barefilterAPI.stores.getCategories(function(data){
            data.unshift({name: "None", id: 0, type_id: 1});
            data.unshift({name: "None", id: 0, type_id: 2});
            $scope.allCategories = data;
            $scope.$apply();
        }, function(){

        });
    };

    var init = function () {
        search();
        getCategories();
    };
    init();
}]);