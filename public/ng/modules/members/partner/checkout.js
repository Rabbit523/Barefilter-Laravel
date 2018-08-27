'use strict';
members.directive('partnerCheckout', ["$state", "usersService", "barefilterAPI", function ($state, usersService, barefilterAPI) {
    return {
        templateUrl: 'ng/modules/members/partner/checkout.html',
        scope: {
        },
        controller: function ($scope) {
            var selectedBuilding, selectedFacility, products;
            var paymentMethod, shippingMethod;
            var succeededPlacingOrder = false;
            $scope.totals = null;
            $scope.$on("totalsUpdate", function(e, data){
                var totals = data.totals;
                products = data.cart;
                if(paymentMethod !== undefined && shippingMethod !== undefined){
                    totals.subtotal += paymentMethod.price;
                    totals.subtotal += shippingMethod.price;
                    totals.shipping = shippingMethod.price;
                }
                totals.tax = Math.round(totals.subtotal * 0.25); // dett inkluderer 25%
                //totals.total = totals.subtotal + totals.tax;
                totals.total = totals.subtotal;
                $scope.totals = totals;
            });

            $scope.$on("shippingUpdate", function(e, data){
                selectedBuilding = data.building;
                selectedFacility = data.facility;
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
                    $state.go('building', {id: selectedBuilding.id});
                }, 1500);
            };
            var getPayload = function () {
                return {
                    netaxept: false,
                    uid: usersService.getLoggedUserId(),
                    bid: selectedBuilding.id,
                    fid: selectedFacility.id,
                    tas: shippingMethod.handle,
                    shipping_method_id : shippingMethod.id,
                    payment_method_id: paymentMethod.id,
                    promo_code: '',
                    products: products.map(function(p){return {id: p.id, subscription_id: p.subscription_id, total: p.total}}),
                    addresses: {
                        same: true,
                        shipping: selectedBuilding.address,
                        billing: null
                    },
                    summary: $scope.totals
                };
            };

            var init = function(){
                barefilterAPI.stores.cart('1', function (data) {
                    data.payment_methods.forEach(function(method){
                        if(method.handle === "faktura"){
                            paymentMethod = method;
                        }
                    });
                    data.shipping_methods.forEach(function(method){
                        if(method.handle === "helthjem_dpd_domestic"){
                            shippingMethod = method;
                        }
                    });
                }, function(){});
            };
            init();
        }
    };
}]);