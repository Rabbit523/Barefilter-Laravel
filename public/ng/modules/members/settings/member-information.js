'use strict';
members.directive('memberInformation', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
        return {
            templateUrl: 'ng/modules/members/settings/member-information.html',
            scope: {
            },
            controller: function ($scope) {
                $scope.loading = false;
                $scope.user = usersService.getLoggedUser();
                $scope.shipping_id = $scope.user.shipping_id.toString(); 
                $scope.billing_id = $scope.user.billing_id.toString(); 
                $scope.addresses = [];
                $scope.save = function(){
                    $scope.loading = true;
                    $scope.user.shipping_id = parseInt($scope.shipping_id);
                    $scope.user.billing_id = parseInt($scope.billing_id);
                    //$scope.user.properties = JSON.stringify(user.properties);
                    barefilterAPI.users.update($scope.user, function(user){
                        $scope.loading = false;
                        usersService.updateLoggedUser($scope.user);
                        $scope.$apply();
                    }, function(){});
                }


                var loadAddresses = function(){
                    barefilterAPI.users.myAddresses(usersService.getLoggedUserId(), function(addresses){
                        $scope.addresses = addresses;
                        $scope.$apply();
                    }, function(){});
                };
                loadAddresses();

                $scope.$on("reload-addresses", loadAddresses);
            }
        };
    }]);