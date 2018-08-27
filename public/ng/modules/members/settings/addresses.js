'use strict';
members.directive('memberAddresses', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
        return {
            templateUrl: 'ng/modules/members/settings/addresses.html',
            scope: {
            },
            controller: function ($scope) {
                $scope.loading = false;
                $scope.addresses = [];
                $scope.hasAddresses = function(){
                    return $scope.addresses.length > 0;
                };
                $scope.addAddress = function(){
                    $scope.loading = true;
                    barefilterAPI.users.addAddress($scope.newAddress, function(address){
                        $scope.$emit("addresses-change");
                        $scope.addresses.push(address);
                        $scope.loading = false;
                        initAddress();
                        $scope.$apply();
                    }, function(){});
                };

                $scope.deleteAddress = function(address){
                    var user = usersService.getLoggedUser();
                    if(user.shipping_id !== address.id && user.billing_id !== address.id){
                        $scope.loading = true;
                        barefilterAPI.users.deleteAddress(address, function(address){
                            $scope.$emit("addresses-change");
                            $scope.addresses.splice($scope.addresses.indexOf(address), 1);
                            $scope.loading = false;
                            initAddress();
                            $scope.$apply();
                        }, function(){});
                    }else{
                        alert("Cannot delete address since it's used either as shipping or billing address");
                    }
                    
                };

                var initAddress = function(){
                    $scope.newAddress = {first_name: "", last_name: "", street_address: "", postal_code: "", city: "", phone: "", email: "", uid: usersService.getLoggedUserId()};
                };

                var loadAddresses = function(){
                    barefilterAPI.users.myAddresses(usersService.getLoggedUserId(), function(addresses){
                        $scope.addresses = addresses;
                        $scope.$apply();
                    }, function(){});
                };

                var init = function(){
                    initAddress();
                    loadAddresses();
                };
                init();
            }
        };
    }]);