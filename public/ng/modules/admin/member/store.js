'use strict';
admin.directive('memberStore', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
        return {
            templateUrl: 'ng/modules/admin/member/store.html',
            scope: {
                subscriptions: '='
            },
            controller: function ($scope) {
                var viewing = 0;
                var getTotals = function(){
                    return {
                        goods: 0,
                        shipping: 0,
                        discount: 0,
                        subtotal: 0,
                        total: 0,
                        tax: 0
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
                    var sub = getSubscription(1);
                    product.selectedSubscription = sub;
                    product.subscription_id = sub.id;
                    product.total = 1;
                    product.cost = product.price * product.total;
                    product.cost = product.cost - Math.round(product.cost * sub.discount * 0.01);
                    $scope.results = [];
                    $scope.searchQuery = "";
                    $scope.cart.push(product);
                    $scope.updateCart();
                };
                $scope.updateProduct = function (item) {
                    var discount, cost;
                    if (item.total <= 0) {
                        item.total = 1;
                    }
                    cost = item.price * item.total;
                    item.subscription_id = item.selectedSubscription.id;
                    discount =  item.selectedSubscription.discount;
                    item.cost = cost - Math.round(cost * discount / 100);
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
                        totals.discount += (p.price * p.total - p.cost);
                        totals.subtotal += p.cost;
                    });
                    
                    //totals.discount = Math.round(totals.goods * discount);
                    totals.subtotal = totals.goods - totals.discount;
                    $scope.totals = totals;
                    $scope.$emit("cartUpdate", {cart: $scope.cart, totals: $scope.totals});
                };
                $scope.hasProductsInCart = function(){
                    return $scope.cart.length > 0;
                };

                var getSubscription = function(id){
                    var sub = {};
                    for(var i = 0; i < $scope.subscriptions.length; i++){
                        if($scope.subscriptions[i].id === id){
                            sub = $scope.subscriptions[i];
                            break;
                        }
                    }
                    return sub;
                }
            }
        };
    }]);