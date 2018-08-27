'use strict';
members.directive('partnerInformation', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
        return {
            templateUrl: 'ng/modules/members/settings/partner-information.html',
            scope: {
            },
            controller: function ($scope) {
                $scope.loading = false;
                $scope.user = usersService.getLoggedUser();
                $scope.feedback = "";
                $scope.save = function(){
                    $scope.loading = true;
                    $scope.feedback = "We are saving your information.";
                    //$scope.user.properties = JSON.stringify($scope.user.properties);
                    barefilterAPI.users.update($scope.user, function(data){
                        $scope.user = data;
                        $scope.feedback = "Your information has been successfully updated.";
                        scheduleReset();
                        $scope.$apply();
                    }, function(err){
                        $scope.feedback = err;
                        scheduleReset();
                        $scope.$apply();
                    });
                };


                var scheduleReset = function(){
                    setTimeout(function(){
                        $scope.loading = false;
                        $scope.$apply();
                    }, 1500);
                };
    
            }
        };
    }]);