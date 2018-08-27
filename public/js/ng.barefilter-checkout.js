'use strict';
var barefilterCheckout = angular.module('barefilterCheckout', []);
barefilterCheckout.directive('barefilterCheckout', [function () {
    return {
        templateUrl: 'ng/directives/checkout/view.html',
        scope: {},
        controller: function ($scope, $rootScope) {
            var paymentMethod, shippingMethod, succeededPlacingOrder = false, step = 1, discountCode = "";

            $scope.shipping_config = config.free_shipping_amount;
            $scope.cart = [];
            $scope.subscriptions = [];
            $scope.loading = false;
            $scope.loadingServicePartners = false;
            $scope.isCreatingAddresses = true;
            $scope.addresses = { same: true };
            $scope.shippingAddress = {};
            $scope.billingAddress = {};
            $scope.hasPlacedOrder = false;
            $scope.selectedServicePartner = null;
            $scope.accountAvailable = true;
            $scope.company = {};
            $scope.notes = {};
            $scope.extras = { guest: false };
            $rootScope.totalCart = 0;
            $scope.isEditedShippingAddress = false;
            $scope.isEditedBillingAddress = false;

            $scope.paymentMethods = [];
            $scope.shippingMethods = [];
            $scope.servicePartners = [];
            $scope.redeemedCode = false;
            $scope.totalDiscount = 0;
            $scope.acceptToss = false;

            $scope.changeAccept = function () {
                $scope.acceptToss = !$scope.acceptToss
            }

            $scope.isStep = function (test) {
                return step === test;
            };

            $scope.next = function () {
                step++;
                if (step === 3) {
                    $scope.selectShipping(shippingMethod);
                }
                $('html, body').stop().animate({
                    scrollTop: 50 + step * 20
                }, 1000);
            };
            $scope.back = function () {
                step--;
                $('html, body').stop().animate({
                    scrollTop: 50 + step * 20
                }, 1000);
            };
            $scope.isLoggedIn = function () {
                return user !== null;
            };

            $scope.hasProducts = function () {
                return $scope.cart.length > 0;
            };

            $scope.isUsingNetaxept = function () {
                return paymentMethod.handle === "netaxept";
            };

            $scope.hasValidAddresses = function () {
                if ($scope.addresses.same) {
                    return isValidAddress($scope.shippingAddress);
                } else {
                    return isValidAddress($scope.shippingAddress) && isValidAddress($scope.billingAddress);
                }
            };
            $scope.isValidEmail = function (emailAddress) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(emailAddress);
            };
            $scope.canMoveToShippingDetails = function () {
                return ($scope.extras.guest) ?
                    //($scope.hasValidAddresses() && $scope.accountAvailable) : 
                    true :
                    ($scope.hasValidAddresses() && $scope.isValidEmail($scope.shippingAddress.email) && $scope.accountAvailable);
            }

            $scope.isHomeDelivery = function () {
                return shippingMethod.handle === "helthjem_home_delivery";
            };

            $scope.needsToSelectServicePartner = function () {
                return shippingMethod.handle === "helthjem_mypack";
            };
            $scope.needsToAddCompanyInfo = function () {
                return shippingMethod.handle === "helthjem_dpd_domestic";
            };

            $scope.hasValidShippingMethod = function () {
                if ($scope.needsToSelectServicePartner()) {
                    return $scope.selectedServicePartner !== null;
                } else if ($scope.needsToAddCompanyInfo()) {
                    return true;
                } else if ($scope.isHomeDelivery()) {
                    return true;
                } else {
                    return false;
                }
            };

            $scope.hasDiscount = function (item) {
                return getSubscriptionDiscount(item.subscription_id) > 0;
            };

            $scope.hasFailedPlacingOrder = function () {
                return ($scope.placingOrder) ? false : !succeededPlacingOrder;
            };

            $scope.hasSuccededPlacingOrder = function () {
                return ($scope.placingOrder) ? false : succeededPlacingOrder;
            };

            $scope.onShippingAddressChange = function (val) {
                if ($scope.addresses.same) {
                    $scope.billingAddress = angular.copy($scope.shippingAddress);
                }
            };

            $scope.createAddresses = function () {
                $scope.shippingAddress = null;
                $scope.billingAddress = null;
                $scope.isCreatingAddresses = true;
            }

            $scope.selectPayment = function (method) {
                paymentMethod = method;
                updateTotals();
            };
            $scope.selectShipping = function (method) {
                shippingMethod = method;
                updateTotals();
                if (shippingMethod.handle === 'helthjem_mypack') {
                    loadPickupPoints();
                } else {
                    $scope.selectedServicePartner = null;
                }
            };
            $scope.selectServicePartner = function (partner) {
                $scope.selectedServicePartner = partner;
            };

            $scope.updateTotals = function (item) {
                var discount, cost;
                if (item.total <= 0) {
                    item.total = 1;
                }
                cost = item.price * item.total;
                discount = getSubscriptionDiscount(item.subscription_id);
                item.cost = cost - Math.round(cost * discount / 100);
                cart.update(item.id, { id: parseInt(item.subscription_id), discount: discount }, item.total);
                updateTotals();
            };
            $scope.deleteFromCart = function (item) {
                $scope.cart.splice($scope.cart.indexOf(item), 1);
                cart.remove(item.id);
                updateTotals();
            };

            $scope.verifyAccountAvailability = function () {
                if (!$scope.isLoggedIn()) {
                    Barefilter.API.registered($scope.shippingAddress.email, function (response) {
                        $scope.accountAvailable = response.success;
                        $scope.$apply();
                    }, function () { });
                }
            };
            $scope.loadShippingCity = function () {
                Barefilter.API.postalCodeLookup($scope.shippingAddress.postal_code, function (response) {
                    if (response.success) {
                        $scope.shippingAddress.city = response.data.result;
                        $scope.onShippingAddressChange();
                        $scope.$apply();
                    }
                });
            };
            $scope.loadBillingCity = function () {
                if (!$scope.addresses.same) {
                    Barefilter.API.postalCodeLookup($scope.billingAddress.postal_code, function (response) {
                        if (response.success) {
                            $scope.billingAddress.city = response.data.result;
                            $scope.$apply();
                        }
                    });
                }
            };

            $scope.hasFreeShipping = function () {
                return config.free_shipping ? $scope.totals.subtotal > config.free_shipping_amount : false;
            };

            $scope.getDiscountForCode = function () {
                $scope.redeemedCode = true;
                discountCode = $("#discount-code").val();
                if (discountCode !== "") {
                    Barefilter.API.getDiscountForCode(discountCode, function (response) {
                        $scope.totalDiscount = response.data;
                        updateTotals()
                        $scope.$apply();
                    });
                }
            };

            $scope.placeOrder = function () {
                var payload = getPayload();
                if ($scope.isLoggedIn()) {
                    payload.uid = user.id;
                }
                if ($scope.selectedServicePartner !== null) {
                    payload.service_partner = $scope.selectedServicePartner;
                }
                if ($scope.needsToAddCompanyInfo()) {
                    payload.company = $scope.company;
                }
                if ($scope.extras.guest) {
                    payload.guest = true;
                }
                if ($scope.isEditedBillingAddress) {
                    payload.addresses.billing.id = null;
                }
                if ($scope.isEditedShippingAddress) {
                    payload.addresses.shipping.id = null;
                }
                $scope.placingOrder = true;
                $scope.hasPlacedOrder = true;
                Barefilter.API.placeOrder(payload, onOrderPlaced);
            };

            $scope.editShippingAddress = function () {
                $scope.addresses.same = false;
                $scope.isCreatingAddresses = "";
                $scope.isEditedShippingAddress = true;
            }

            $scope.editBillingAddress = function () {
                $scope.addresses.same = false;
                $scope.isCreatingAddresses = "";
                $scope.isEditedBillingAddress = true;
            }

            var isValidAddress = function (address) {
                var result = true, properties = ["first_name", "last_name", "phone", "street_address", "postal_code", "city"];
                for (var i = 0; i < properties.length; i++) {
                    if (!address.hasOwnProperty(properties[i]) || address[properties[i]] === "") {
                        result = false;
                        break;
                    }
                }
                return result;
            };
            var loadPickupPoints = function () {
                $scope.loadingServicePartners = true;
                Barefilter.API.pickupPoints($scope.shippingAddress.postal_code, function (response) {
                    $scope.loadingServicePartners = false;
                    if (response.success) {
                        $scope.servicePartners = response.data.partners;
                    }
                    $scope.$apply();
                });
            };
            var onOrderPlaced = function (response) {
                $scope.placingOrder = false;
                succeededPlacingOrder = response.success;
                if (succeededPlacingOrder) {
                    if (!$scope.isUsingNetaxept()) {
                        cart.clear();
                    }
                    if ($scope.isUsingNetaxept()) {
                        redirect(response.data.redirect);
                    } else if (!$scope.extras.guest) {
                        redirect(response.data.redirect + "/" + response.data.order.id);
                    } else {
                        $scope.order = response.data.order;
                    }
                }
                $scope.$apply();
            };
            var redirect = function (redirect) {
                setTimeout(function () {
                    window.location.href = redirect; //response.data.redirect;
                }, 1500);
            };

            var getPayload = function () {
                /*$scope.shippingAddress.email = ($scope.extras.guest) ? "kontakt@barefilter.no" : $scope.shippingAddress.email;
                $scope.billingAddress.email = ($scope.extras.guest) ? "kontakt@barefilter.no" : $scope.shippingAddress.email;*/
                return {
                    netaxept: $scope.isUsingNetaxept(),
                    tas: shippingMethod.handle,
                    shipping_method_id: shippingMethod.id,
                    payment_method_id: paymentMethod.id,
                    promo_code: discountCode,
                    products: cart.getItems(),
                    addresses: {
                        same: $scope.addresses.same,
                        shipping: $scope.shippingAddress,
                        billing: $scope.addresses.same ? null : $scope.billingAddress
                    },
                    summary: $scope.totals,
                    notes: $scope.notes.text
                };
            };
            var getTotals = function () {
                return {
                    goods: 0,
                    shipping: 0,
                    discount: 0,
                    subtotal: 0,
                    total: 0,
                    tax: 0
                };
            };
            var updateTotals = function () {
                var totals = getTotals();
                totals.shipping = shippingMethod.price;
                var count = 0;
                cart.getItems().forEach(function (i) {
                    count += i.total;
                    totals.goods += (i.price * i.total);
                    totals.discount += (i.price * i.total - i.cost);
                    totals.subtotal += i.cost;
                });
                $rootScope.totalCart = count;
                var d = Math.round(totals.subtotal * $scope.totalDiscount / 100);
                totals.subtotal += paymentMethod.price;
                totals.subtotal -= d;
                totals.discount += d;

                totals.tax = Math.round(totals.subtotal * 0.25); // dett inkluderer 25%
                //totals.total = totals.subtotal + totals.tax;
                totals.total = totals.subtotal;
                console.log(totals.subtotal);
                if (!config.free_shipping || totals.subtotal < config.free_shipping_amount) {
                    totals.total += shippingMethod.price;
                }
                $scope.totals = totals;
            };
            var getSubscriptionDiscount = function (sid) {
                var discount = 0;
                sid = parseInt(sid);
                for (var i = 0; i < $scope.subscriptions.length; i++) {
                    if ($scope.subscriptions[i].id === sid) {
                        discount = $scope.subscriptions[i].discount;
                        break;
                    }
                }
                return discount;
            };
            var processResponse = function (data) {
                var items = cart.getItems();
                data.forEach(function (product) {
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].id === product.id) {
                            product.total = items[i].total;
                            product.subscription_id = items[i].subscription_id.toString();
                            product.cost = items[i].cost;
                            break;
                        }
                    }
                });
                return data;
            };
            var onCartLoad = function (response) {
                $scope.loading = false;
                if (response.success) {
                    $scope.cart = processResponse(response.data.products);
                    $scope.subscriptions = response.data.subscriptions;
                    $scope.paymentMethods = response.data.payment_methods;
                    $scope.shippingMethods = response.data.shipping_methods.map(function (method) {
                        method.price = method.price;
                        return method;
                    });
                    paymentMethod = $scope.paymentMethods[0];
                    $scope.shippingMethods.forEach(function (m) { if (m.handle === 'helthjem_mypack') { shippingMethod = m; } });

                    updateTotals();
                    $scope.$apply();
                }
            };
            var onMeLoad = function (response) {
                user = response.data;
                $scope.shippingAddress = user.shipping;
                $scope.billingAddress = user.billing;
                if (user.shipping !== null && user.billing !== null) {
                    $scope.isCreatingAddresses = false;
                }
            };
            var init = function () {
                var ids = cart.getIds();
                if (ids.length > 0) {
                    $scope.loading = true;
                    Barefilter.API.cart(ids, onCartLoad);
                }
                if (user !== null) {
                    Barefilter.API.me(onMeLoad);
                }
            };
            init();
        }
    };
}]);