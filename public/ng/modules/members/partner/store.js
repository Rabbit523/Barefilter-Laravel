'use strict';
members.directive('partnerStore', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
        return {
            templateUrl: 'ng/modules/members/partner/store.html',
            scope: {
            },
            controller: function ($scope) {
                var discount = usersService.getLoggedUserDiscount() / 100;
                var viewing = 0;
                var getTotals = function(){
                    return {
                        goods: 0,
                        shipping: 0,
                        discount: 0,
                        subtotal: 0,
                        total: 0,
                        tax: 0,
                        rate: usersService.getLoggedUserDiscount()
                    };
                };
                
                $scope.searchQuery = "";
                $scope.cart = [];
                $scope.results = [];
                $scope.totals = getTotals();
            
                $scope.search = function(){
                    barefilterAPI.stores.search($scope.searchQuery, function(data){
                        $scope.results = data.products;
                        $scope.$apply();
                    }, function(){
            
                    });
                };
                $scope.hasResults = function(){
                    return $scope.results.length > 0;
                };
                $scope.addToCart = function(product){
                    product.subscription_id = 1;
                    product.total = 1;
                    product.cost = product.price * product.total;
                    product.cost = product.cost - (product.cost * discount);
                    $scope.results = [];
                    $scope.searchQuery = "";
                    $scope.cart.push(product);
                    $scope.updateCart();
                };
                $scope.removeFromCart = function(product){
                    $scope.cart.splice($scope.cart.indexOf(product), 1);
                    $scope.updateCart();
                };
                $scope.updateCart = function(){
                    var totals = getTotals();
                    $scope.cart.forEach(function(p){
                        totals.goods += p.price * p.total;
                    });
                    
                    totals.discount = Math.round(totals.goods * discount);
                    totals.subtotal = totals.goods - totals.discount;
                    $scope.totals = totals;
                    $scope.$emit("cartUpdate", {cart: $scope.cart, totals: $scope.totals});
                };
                $scope.hasProductsInCart = function(){
                    return $scope.cart.length > 0;
                };
            }
        };
    }]);