'use strict';
admin.controller("salesController", ["$rootScope", "$scope", "usersService", "barefilterAPI", function ($rootScope, $scope, usersService, barefilterAPI) {
    $rootScope.title = "Orders";
    var startDate, endDate;
    $scope.orders = [];
    $scope.loading = false;
    $scope.selectedSubscription = "0";
    $scope.searching = false;


    $scope.hasOrders = function () {
        return $scope.orders.length > 0;
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

    $scope.delete = function (order) {
        if (confirm("Are you sure you want to delete this order?")) {
            barefilterAPI.orders.delete({ oid: order.id }, function (data) {
                loadTimeframedHistory();
            }, function () { });
        }
    };

    $scope.reload = function(){
        loadTimeframedHistory();
    };

    $scope.$watch('searchQuery', function (val) {
        search();
    });

    $scope.exportToExcel = function(){
        var dateFormat = "YYYY-MM-DD";
        barefilterAPI.orders.exportToExcel(
            usersService.getLoggedUserId(),
            $scope.selectedSubscription,
            startDate.format(dateFormat),
            endDate.format(dateFormat));
    };

    var search = function () {
        if (!$scope.loading && $scope.searching) {
            $scope.loading = true;
            barefilterAPI.orders.search($scope.searchQuery, function (data) {
                $scope.loading = false;
                $scope.orders = data;
                $scope.$apply();
            }, function () {

            });
        }else{
            setTimeout(search, 100);
        }

    };


    var loadTimeframedHistory = function () {
        $scope.loading = true;
        var dateFormat = "YYYY-MM-DD";
        barefilterAPI.orders.getTimeframedHistory(
            usersService.getLoggedUserId(),
            $scope.selectedSubscription,
            startDate.format(dateFormat),
            endDate.format(dateFormat),
            function (data) {
                $scope.loading = false;
                $scope.orders = data.filter(function(order){return order.products.length > 0;});
                $scope.$apply();
            }, function () { });
    };

    var loadSubscriptionTypes = function(){
        barefilterAPI.orders.getSubscriptionTypes(function(data){
            data.unshift({id: "0", name: "All"});
            $scope.subscriptions = data;
            $scope.$apply();
        }, function(){});
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
            maxDate: endDate
        }, onDaterangeChange);
    };

    var init = function () {
        startDate = moment().startOf("month");
        endDate = moment();
        initDaterangePicker();
        loadSubscriptionTypes();
        loadTimeframedHistory();
    };
    init();
}]);