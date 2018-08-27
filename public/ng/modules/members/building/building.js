'use strict';
members.controller("buildingController", ["$rootScope", "$scope", "$stateParams", "usersService", "barefilterAPI", function ($rootScope, $scope, $stateParams, usersService, barefilterAPI) {
    $rootScope.title = "Building Profile";
    
    var loadBuildingProfile = function () {
        barefilterAPI.buildings.profile($stateParams.id, function (building) {
            $scope.building = building;
            $scope.$apply();
        }, function (err) { });
    };

    var init = function () {
        loadBuildingProfile();  
    };
    init();
}]);