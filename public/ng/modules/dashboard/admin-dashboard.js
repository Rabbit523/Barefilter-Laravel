'use strict';
dashboard.directive('adminDashboard', ["$state", "barefilterAPI", function ($state, barefilterAPI) {
    return {
        templateUrl: 'ng/modules/dashboard/admin-dashboard.html',
        scope: {
        },
        controller: function ($scope) {
            var startDate, endDate, histogram = [];
            $scope.aggregates = {orders: 0, sales: 0, discounts: 0};
            $scope.loading = false;
            $scope.top = [];

            $scope.hasTopTen = function(){
                return $scope.top.length > 0;
            };


            var loadDashboard = function () {
                $scope.loading = true;
                var dateFormat = "YYYY-MM-DD";
                barefilterAPI.orders.getDashboard(
                    startDate.format(dateFormat),
                    endDate.format(dateFormat),
                    function (data) {
                        $scope.loading = false;
                        $scope.aggregates = data.aggregates;
                        histogram = data.sales_histogram;
                        $scope.top = data.top_ten;
                        (histogram.length > 0 ) ? drawChart() : {};
                        $scope.$apply();
                    }, function () { });
            };

            var drawChart = function() {
                var arr = [['Date', 'Sales', 'Discounts']];
                histogram.forEach(function(bin){
                    arr.push([bin.date, bin.sales, bin.discounts]);
                });

                var options = {
                  title: 'Company Performance',
                  legend: { position: 'bottom' },
                  animation: {
                      startup: true,
                      duration: 1200,
                      easing: 'inAndOut'
                  },
                  backgroundColor: 'transparent'
                };
        
                var chart = new google.visualization.LineChart(document.getElementById('performance-chart'));
        
                chart.draw(google.visualization.arrayToDataTable(arr), options);
              }

            var onGoogleChartsReady = function(){
                loadDashboard();
            };

            var onDaterangeChange = function (start, end, label) {
                startDate = start;
                endDate = end;
                loadDashboard();
            };

            var initDaterangePicker = function () {
                $('#dashboard-daterange-picker').daterangepicker({
                    startDate: startDate,
                    endDate: endDate,
                    maxDate: endDate
                }, onDaterangeChange);
            };

            var init = function(){
                startDate = moment().startOf("month");
                endDate = moment();
                initDaterangePicker();
                google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(loadDashboard);
            };

            init();
        }
    };
}]);