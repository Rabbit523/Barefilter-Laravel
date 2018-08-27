'use strict';
members.controller("partnerController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    var selectedBuilding = null, selectedFacility = null;
    $rootScope.title = "Partner";
    $scope.hasSelectedFacility = function () {
        return selectedFacility !== null;
    };

    $scope.$on("facilitySelection", function (e, data) {
        selectedFacility = data.facility;
        $scope.$broadcast("shippingUpdate", data);
    });

    $scope.$on("cartUpdate", function (e, data) {
        $scope.$broadcast("totalsUpdate", data);
    });

}]);