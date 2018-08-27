'use strict';
members.controller("subscriptionsController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Mine filterabonnement";
    var paginator = {};
    $scope.products = [];
    $scope.loading = false;


    $scope.hasTransactions = function () {
        return $scope.products.length > 0;
    };


    $scope.cancelSubscription = function (p) {
        if (confirm("Are you sure you want to cancel this subscription?")) {
            var data = { opid: p.opid };
            barefilterAPI.orders.cancelSubscription(data, function (op) {
                loadMyTransactions();
            }, function () { });
        }

    };

    var parseResults = function (orders) {
        var products = [];
        orders.forEach(function (order) {
            order.products.forEach(function (p) {
                var product = p.product;
                product.opid = p.id;
                product.order_identifier = order.identifier;
                product.subscription_id = p.subscription_id;
                product.amount = p.amount;
                product.shipping = order.shipping;
                product.purchase_date = new Date(order.created_at);
                product.first_delivery_date = moment(order.created_at).add(6, 'month').toDate();
                product.second_delivery_date = moment(order.created_at).add(12, 'month').toDate()
                products.push(product);
            });
        });
        return products;
    };

    var loadMyTransactions = function () {
        $scope.loading = true;
        barefilterAPI.orders.getSubscriptionsByUserId(usersService.getLoggedUserId(), function (_paginator) {
            paginator = _paginator;
            $scope.loading = false;
            $scope.products = parseResults(paginator.data);
            $scope.$apply();
        }, function () { });
    };
    var init = function () {
        loadMyTransactions();
    };
    init();
}]);