application.factory("storesAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "stores";
    extended.search = function (q, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/search/" + q, {}, onSuccess, onError);
    };

    extended.productCheck = function (name, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/product-check", {name: name}, onSuccess, onError);
    };

    extended.categoryCheck = function (name, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/category-check", {name: name}, onSuccess, onError);
    };

    extended.cart = function(q, onSuccess, onError){
        apiRequest.ajax("GET", endpoint + "/cart/" + q, {}, onSuccess, onError);
    };


    extended.getDiscounts = function(onSuccess, onError){
        apiRequest.ajax("GET", endpoint + "/discounts", {}, onSuccess, onError);
    };
    extended.createDiscount = function(discount, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/create-discount", discount, onSuccess, onError);
    };
    extended.updateDiscount = function(discount, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/update-discount", discount, onSuccess, onError);
    };

    extended.searchCategories = function (q, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/search-categories/" + q, {}, onSuccess, onError);
    };
    extended.getCategories = function(onSuccess, onError){
        apiRequest.ajax("GET", endpoint + "/categories", {}, onSuccess, onError);
    };
    extended.createCategory = function(category, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/create-category", category, onSuccess, onError);
    };
    extended.updateCategory = function(category, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/update-category", category, onSuccess, onError);
    };
    extended.deleteCategory = function(category, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/delete-category", category, onSuccess, onError);
    };

    extended.getProducts = function(onSuccess, onError){
        apiRequest.ajax("GET", endpoint + "/products", {}, onSuccess, onError);
    };
    extended.createProduct = function(product, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/create-product", product, onSuccess, onError);
    };
    extended.updateProduct = function(product, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/update-product", product, onSuccess, onError);
    };

    extended.getProductImageUploadURL = function(){
        return config.server + endpoint + "/add-product-image";
    };

    extended.deleteProductImage = function(id, onSuccess, onError){
        apiRequest.ajax("POST", endpoint + "/delete-product-image", {id: id}, onSuccess, onError);
    };

    extended.getCategoryImageUploadURL = function(){
        return config.server + endpoint + "/category-image";
    };

    extended.getCategoryItemImageUploadURL = function () {
        return config.server + endpoint + "/category-item-image";
    };
    return extended;
}]);