'use strict';
admin.controller("subscriptionsBrowserController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Abonnementer";
    var startDate, endDate;
    $scope.products = [];
    $scope.loading = false;
    


    $scope.hasUpcomingOrders = function () {
        return $scope.products.length > 0;
    };

    $scope.getPurchaseDate = function (date) {
        return new Date(date);
    };

    $scope.getStartDate = function () {
        return startDate.toDate();
    };

    $scope.getEndDate = function () {
        return endDate.toDate();
    };
    
    $scope.deleteSubscription = function (product) {
        if (confirm("Are you sure you want to delete this subscription order?")) {
            barefilterAPI.orders.deleteSubscription({ id: product.id }, function (data) {
                loadTimeframedHistory();
            }, function () { });
        }
    };
    
    var loadTimeframedHistory = function () {
        $scope.loading = true;
        var dateFormat = "YYYY-MM-DD";
        barefilterAPI.orders.browseSubscriptions(
            startDate.format(dateFormat),
            endDate.format(dateFormat),
            function (data) {
                $scope.loading = false;
                $scope.products = data;
                $scope.$apply();
            }, function () { });
    };

    

    var onDaterangeChange = function (start, end, label) {
        startDate = start;
        endDate = end;
        loadTimeframedHistory();
    };

    var initDaterangePicker = function () {
        $('#sales-daterange-picker').daterangepicker({
            startDate: startDate,
            endDate: endDate,
            minDate: startDate
        }, onDaterangeChange);
    };

    var init = function () {
        startDate = moment();
        endDate = moment().endOf('month');
        initDaterangePicker();
        loadTimeframedHistory();
    };
    init();
}]);