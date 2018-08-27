'use strict';
admin.directive('memberCheckout', ["$state", "barefilterAPI", function ($state, barefilterAPI) {
    return {
        templateUrl: 'ng/modules/admin/member/checkout.html',
        scope: {
            user: '='
        },
        controller: function ($scope) {
            var products, shippingMethod, paymentMethod;
            var succeededPlacingOrder = false;
            $scope.totals = null;
            $scope.$on("totalsUpdate", function(e, data){
                var totals = data.totals;
                products = data.cart;
                if(data.paymentMethod !== undefined && data.shippingMethod !== undefined){
                    shippingMethod = data.shippingMethod;
                    paymentMethod = data.paymentMethod;
                    totals.subtotal += data.paymentMethod.price;
                    totals.subtotal += data.shippingMethod.price;
                    totals.shipping = data.shippingMethod.price;
                }
                totals.tax = Math.round(totals.subtotal * 0.25); // dett inkluderer 25%
                //totals.total = totals.subtotal + totals.tax;
                totals.total = totals.subtotal;
                $scope.totals = totals;
                $scope.cart = products;
            });


            $scope.hasAnything = function(){
                return $scope.totals !== null && $scope.totals.total > 0;
            };

            $scope.hasFailedPlacingOrder = function () {
                return ($scope.placingOrder) ? false : !succeededPlacingOrder;
            };

            $scope.hasSuccededPlacingOrder = function () {
                return ($scope.placingOrder) ? false : succeededPlacingOrder;
            };

            $scope.placeOrder = function () {
                var payload = getPayload();
                $scope.placingOrder = true;
                $scope.hasPlacedOrder = true;
                barefilterAPI.orders.place(payload, onOrderPlaced, function(){
                    $scope.placingOrder = false;
                    succeededPlacingOrder = false;
                    $scope.$apply();
                })
            };


            var onOrderPlaced = function (data) {
                $scope.placingOrder = false;
                succeededPlacingOrder = true;
                $scope.$apply();
                setTimeout(function () {
                    $scope.$emit("orderPlaced");
                }, 1500);
            };

            var getPayload = function () {
                return {
                    netaxept: false,
                    uid: $scope.user.id,
                    tas: shippingMethod.handle,
                    shipping_method_id : shippingMethod.id,
                    payment_method_id: paymentMethod.id,
                    promo_code: '',
                    products: products.map(function(p){return {id: p.id, subscription_id: p.subscription_id, total: p.total}}),
                    addresses: {
                        same: true,
                        shipping: $scope.user.shipping,
                        billing: null
                    },
                    summary: $scope.totals
                };
            };
        }
    };
}]);