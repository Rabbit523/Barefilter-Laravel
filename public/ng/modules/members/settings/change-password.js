'use strict';
members.directive('changePassword', ["usersService", "barefilterAPI", function (usersService, barefilterAPI){
        return {
            templateUrl: 'ng/modules/members/settings/change-password.html',
            scope: {
            },
            controller: function ($scope) {
                $scope.loading = false;
                $scope.feedback = "";
                $scope.password = {};
                
                $scope.save = function(){
                    $scope.loading = true;
                    $scope.feedback = "We are saving your new password.";
                    barefilterAPI.users.changePassword($scope.password, function(data){
                        $scope.feedback = "Your password has been successfully updated."
                        $scope.$apply();
                        scheduleReset();
                    }, function(data){
                        $scope.feedback = data;
                        $scope.$apply();
                        scheduleReset();
                    });
                }

                $scope.validate = function(){
                    if($scope.password.new !== $scope.password.repeat){
                        $scope.loading = true;
                        $scope.feedback = "Passwords do not match.";
                    }else{
                        $scope.feedback = "";
                        $scope.loading = false;
                    }
                };
                var scheduleReset = function(){
                    setTimeout(function(){
                        reset();
                        $scope.$apply();
                    }, 1500);
                };
                var initPassword = function(){
                    $scope.password = {uid: usersService.getLoggedUserId(), old: "", new: "", repeat: ""};
                };
                var reset = function(){
                    $scope.loading = false;
                    initPassword();
                };
                reset();
            }
        };
    }]);