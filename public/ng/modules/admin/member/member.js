'use strict';
admin.controller("memberController", ["$rootScope", "$scope", "$stateParams", 'barefilterAPI', function ($rootScope, $scope, $stateParams, barefilterAPI) {
    $rootScope.title = "Member";
    
    var paymentMethod, shippingMethod;
    $scope.user = {orders: []};
    $scope.loading = false;
    $scope.creatingOrder = false;
    
    $scope.hasOrders = function(){
        return  $scope.user.orders.length > 0;
    };

    $scope.getPurchaseDate = function (date) {
        return new Date(date);
    };

    $scope.getPhone = function(user){
        return (user.phone !== "") ? user.phone : "No phone";
    };


    $scope.createOrder = function(){
        $scope.creatingOrder = true;
    };
    $scope.cancelOrderCreation = function(){
        $scope.creatingOrder = false;
    };

    $scope.$on("cartUpdate", function (e, data) {
        data.paymentMethod = paymentMethod;
        data.shippingMethod = shippingMethod;
        $scope.$broadcast("totalsUpdate", data);
    });

    $scope.$on("orderPlaced", function (e, data) {
        $scope.creatingOrder = false;
        loadProfile();
    });

    var loadStoreDefaultSettings = function(){
        barefilterAPI.stores.cart('1', function (data) {
            $scope.subscriptions = data.subscriptions;

            data.payment_methods.forEach(function(method){
                if(method.handle === "faktura"){
                    paymentMethod = method;
                }
            });
            data.shipping_methods.forEach(function(method){
                method.price = (config.free_shipping) ? 0 : method.price;
                if(method.handle === "helthjem_dpd_domestic"){
                    shippingMethod = method;
                }
            });
        }, function(){});
    };

    var loadProfile = function(){
        $scope.loading = true;
        barefilterAPI.users.profile($stateParams.id, function(data){
            $scope.loading = false;
            $scope.user = data;
            $scope.$apply();
        }, function(){

        });
    };
    var init = function () {
        loadProfile();
        loadStoreDefaultSettings();
    };
    init();
}]);