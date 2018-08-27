'use strict';
members.controller("memberSettingsController", ["$rootScope", "$scope", function ($rootScope, $scope) {
    $rootScope.title = "Brukerinnstillinger";

    $scope.$on("addresses-change", function(){
        $scope.$broadcast("reload-addresses");
    });
    var init = function () {
        
    };
    init();
}]);