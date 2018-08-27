'use strict';
admin.controller("ordersController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Orders";
    
    var paginator = {};
    $scope.orders = [];
    $scope.loading = false;


    $scope.hasOrders = function(){
        return $scope.orders.length > 0;
    };

    $scope.getPurchaseDate = function(date){
        return new Date(date);
    };

    $scope.delete = function(order){
        if(confirm("Are you sure you want to delete this order?")){
            barefilterAPI.orders.delete({oid: order.id}, function(data){
                loadMyTransactions();
            }, function(){});
        }
    };

    
    var loadMyTransactions = function(){
        $scope.loading = true;
        barefilterAPI.orders.getHistory(usersService.getLoggedUserId(), function(_paginator){
            paginator = _paginator;
            $scope.loading = false;
            $scope.orders = paginator.data;
            $scope.$apply();
        }, function(){});
    };
    var init = function () {
        loadMyTransactions();  
    };
    init();
}]);