'use strict';
admin.controller("partnersController", ["$rootScope", "$scope", "barefilterAPI", function ($rootScope, $scope, barefilterAPI) {
    $rootScope.title = "Partners";
    $scope.searchQuery = "";
    $scope.users = [];
    $scope.loading = false;

    var modal = "#create-product-modal";

    $scope.triggerSearch = function(){
        search();
    };

    $scope.hasMembers = function(){
        return $scope.users.length > 0;
    };

    $scope.getPhone = function(user){
        return (user.phone !== "") ? user.phone : "No phone";
    };

    $scope.delete = function (user) {
        if (confirm("Are you sure you want to delete this partner?")) {
            barefilterAPI.users.delete({ uid: user.id }, function (data) {
                search();
            }, function () { });
        }
    };

    $scope.addNew = function(){
        $scope.isAddingNew = true;
        $scope.partner = getDefaultUser();
        $(modal).modal('show');
    };

    $scope.edit = function(partner){
        $scope.isAddingNew = false;
        $scope.partner = partner;
        $(modal).modal('show');
    };

    $scope.save = function(){
        $(modal).modal('hide');
        var payload = angular.copy($scope.partner);
        //payload.properties = JSON.stringify(payload.properties);
        barefilterAPI.users.createPartner(payload, function(data){
            search();
        }, function(){});
    };

    $scope.update = function(){
        $(modal).modal('hide');
        var payload = angular.copy($scope.partner);
        //payload.properties = JSON.stringify(payload.properties);
        barefilterAPI.users.update(payload, function(data){
        }, function(){});
    };

    var getDefaultUser = function(){
        return {
            first_name: "",
            last_name : "",
            email: "",
            phone: ""
        }
    };

    var search = function(){
        $scope.loading = true;
        barefilterAPI.users.searchPartners($scope.searchQuery, function(data){
            $scope.users = data;
            $scope.loading = false;
            $scope.$apply();
        }, function(){

        });
    };
    var init = function () {
        search();
    };
    init();
}]);