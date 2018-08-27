'use strict';
members.controller("buildingsController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Mine bygg";
    

    var getDefaultBuilding = function () {
        var user = usersService.getLoggedUser();
        return {
            uid: user.id,
            name: "",
            first_name: user.first_name,
            last_name: user.last_name,
            phone: user.phone,
            email: user.email,
            street_address: "",
            postal_code: "",
            city: ""
        };
    };

    var loadMyBuildings = function () {
        barefilterAPI.buildings.mine(usersService.getLoggedUserId(), function (buildings) {
            $scope.buildings = buildings;
            $scope.$apply();
        }, function (err) { });
    };
    var init = function () {
        loadBuildings();
    };
    var init = function () {
        loadMyBuildings();  
    };
    init();



    $scope.loading = false;
    $scope.buildings = [];
    $scope.newBuilding = getDefaultBuilding();

    $scope.hasBuildings = function(){
        return $scope.buildings.length > 0;
    };

    $scope.getNamesOfFacilities = function(building){
        return (building.facilities !== undefined && building.facilities.length > 0) ? building.facilities.map(function(f){return f.name;}).join(", ") : "No facilities";
    };

    $scope.saveBuilding = function () {
        $scope.loading = true;
        barefilterAPI.buildings.add($scope.newBuilding, function (building) {
            $scope.loading = false;
            $scope.newBuilding = getDefaultBuilding();
            loadMyBuildings();
        }, function () { });
    };
}]);