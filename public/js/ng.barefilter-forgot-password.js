'use strict';
var barefilterForgotPassword = angular.module('barefilterForgotPassword', []);
barefilterForgotPassword.directive('barefilterForgotPassword', [function () {
    return {
        templateUrl: '/ng/directives/forgot-password/view.html',
        scope: {

        },
        controller: function ($scope) {
            var step, uid, resetCode, hasError, loading;

            $scope.isStep = function (tstep) {
                return step === tstep;
            };

            $scope.hasError = function () {
                return hasError;
            };

            $scope.isLoading = function () {
                return loading;
            };

            $scope.getCode = function () {
                loading = true;
                Barefilter.API.passwordCode($scope.email, function (response) {
                    loading = false;
                    if (response.success) {
                        resetCode = response.data.code;
                        uid = response.data.uid;
                        next();
                    } else {
                        hasError = true;
                    }
                    $scope.$apply();
                });

            };

            $scope.testCode = function () {
                loading = true;
                if ($scope.isValidCode()) {
                    setTimeout(function () {
                        next();
                        $scope.$apply();
                    }, 1000);
                } else {
                    hasError = true;
                    loading = false;
                }
            };

            $scope.isValidCode = function () {
                return $scope.resetCode !== "" && resetCode === $scope.resetCode;
            };

            $scope.updatePassword = function () {
                loading = true;
                if ($scope.isValidPassword()) {
                    var payload = { new: $scope.password, uid: uid, code: resetCode };
                    Barefilter.API.resetPassword(payload, function (response) {
                        loading = false;
                        if (response.success) {
                            next();
                        } else {
                            hasError = true;
                        }
                        $scope.$apply();
                    });
                } else {
                    hasError = true;
                }
            };

            $scope.isValidPassword = function () {
                return ($scope.password !== "" && $scope.repeatPassword !== "" && $scope.password === $scope.repeatPassword);
            };

            $scope.login = function () {
                loading = true;
                Barefilter.API.login($scope.username, $scope.userPassword, function (response) {
                    loading = false;
                    if (response.success) {
                        $scope.user = response.data.user;
                        window.location = response.data.redirect;
                    } else {
                        hasError = true;
                    }
                    $scope.$apply();
                });
            };

            var next = function () {
                step++;
                hasError = false;
                loading = false;
            }

            var init = function () {
                step = 1;
                loading = false;
                hasError = false;

                $scope.resetCode = "";
                $scope.password = "";
                $scope.repeatPassword = "";

            };
            init();
        }
    };
}]);