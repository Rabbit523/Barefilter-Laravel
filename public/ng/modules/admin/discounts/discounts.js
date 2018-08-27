'use strict';
admin.controller("discountsController", ["$rootScope", "$scope", "$filter", "barefilterAPI", function ($rootScope, $scope, $filter, barefilterAPI) {
    $rootScope.title = "Rabattkoder";
    $scope.discounts = [];
    $scope.isAddingNew = false;
    $scope.selectedDiscount = {};
    var modal = "#discount-settings-modal";

    $scope.hasDiscounts = function(){
        return $scope.discounts.length > 0;
    };

    $scope.addNew = function(){
        $scope.isAddingNew = true;
        $scope.selectedDiscount = getDefaultDiscount();
        $(modal).modal('show');
    };

    $scope.edit = function(discount){
        $scope.isAddingNew = false;
        $scope.selectedDiscount = discount;
        $(modal).modal('show');
    };


    $scope.save = function(){
        $(modal).modal('hide');
        var payload = angular.copy( $scope.selectedDiscount);
        payload.start_date = formatDate(payload.start_date);
        payload.end_date = formatDate(payload.end_date);
        barefilterAPI.stores.createDiscount(JSON.parse(angular.toJson(payload)), function(data){
            loadDiscounts();
        }, function(){});
    };

    $scope.update = function(){
        $(modal).modal('hide');
        var payload = angular.copy( $scope.selectedDiscount);
        payload.start_date = formatDate(payload.start_date);
        payload.end_date = formatDate(payload.end_date);
        barefilterAPI.stores.updateDiscount(JSON.parse(angular.toJson(payload)), function(data){
        }, function(){});
    };

    var formatDate = function(date){
        return $filter('date')(date, "yyyy-MM-dd HH:mm:ss");
    }

    var getDefaultDiscount = function(){
        return {
            active: 1,
            name : "",
            code: "",
            value: 0,
            start_date: "",
            end_date: ""
        }
    };

    var loadDiscounts = function(){
        barefilterAPI.stores.getDiscounts(function(data){
            $scope.discounts = data.map(function(d){
                d.start_date = new Date(d.start_date);
                d.end_date = new Date(d.end_date);
                return d;
            });
            $scope.$apply();
        }, function(){});
    }

    var init = function () {
        loadDiscounts();
    };
    init();
}]);