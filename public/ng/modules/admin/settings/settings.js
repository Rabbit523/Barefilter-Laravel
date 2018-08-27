'use strict';
admin.controller("settingsController", ["$rootScope", "$scope", 'barefilterAPI', function ($rootScope, $scope, barefilterAPI) {
    $rootScope.title = "Settings";

    $scope.settings = {};
    $scope.saving = false;

    $scope.save = function(){
        $scope.saving = true;
        var obj = angular.copy($scope.settings);
        obj.configuration = JSON.stringify(obj.configuration);
        barefilterAPI.settings.update(obj, function(data){
            $scope.settings = data;
            $scope.saving = false;
            $scope.$apply();
        }, function(){
            $scope.saving = false;
            $scope.$apply();
        });
    };
    var init = function () {
        barefilterAPI.settings.get(function(data){
            $scope.settings = data;
            $scope.$apply();
        }, function(){});
    };
    init();
}]);