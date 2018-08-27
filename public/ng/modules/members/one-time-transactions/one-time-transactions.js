'use strict';
members.controller("oneTimeTransactionsController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Mine engangskjøp";
    
    var paginator = {};
    $scope.products = [];
    $scope.loading = false;
    $scope.selectedProduct = {};
    $scope.selectedSubscription = null;
    $scope.newPrice = 0;

    $scope.setupTransfer = function(p){
        $scope.selectedProduct = p;
        $("#transfer-to-subscription-modal").modal('show');
    };

    $scope.updatePreview = function(){
        $scope.newPrice = ($scope.selectedSubscription !== null) ? $scope.selectedProduct.price - Math.round($scope.selectedProduct.price * $scope.selectedSubscription.discount / 100) : 0;
        $scope.newPrice = $scope.newPrice * $scope.selectedProduct.amount;
    };

    $scope.transfer = function(){
        var data = {opid: $scope.selectedProduct.opid, sid: $scope.selectedSubscription.id, amount: $scope.selectedProduct.amount};
        barefilterAPI.orders.transferSubscription(data, function(op){
            loadMyTransactions();
        }, function(){});
        $scope.close();
    };

    $scope.close = function(){
        $scope.selectedSubscription = null;
        $scope.newPrice = 0;
        $("#transfer-to-subscription-modal").modal('hide');
    };

    $scope.hasTransactions = function(){
        return $scope.products.length > 0;
    };

    var parseResults = function(orders){
        var products = [];
        orders.forEach(function(order){
            order.products.forEach(function(p){
                var product = p.product;
                product.order_identifier = order.identifier;
                product.amount = p.amount;
                product.shipping = order.shipping;
                product.purchase_date = new Date(order.created_at);
                product.opid = p.id;
                products.push(product);
            });
        });
        return products;
    };

    var loadMyTransactions = function(){
        $scope.loading = true;
        barefilterAPI.orders.getOneTimeTransactionsByUserId(usersService.getLoggedUserId(), function(_paginator){
            paginator = _paginator;
            $scope.loading = false;
            $scope.products = parseResults(paginator.data);
            $scope.$apply();
        }, function(){});
    };

    var loadSubscriptionTypes = function(){
        barefilterAPI.orders.getSubscriptionTypes(function(types){
            $scope.subscriptionTypes = types.filter(function(t){ return t.discount > 0; });
        }, function(){});
    };
    var init = function () {
        loadMyTransactions();  
        loadSubscriptionTypes();
    };
    init();
}]);