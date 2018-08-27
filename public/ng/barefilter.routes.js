'use strict';
barefilterApp.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/');
        $urlRouterProvider.when('', '/');
        $stateProvider
                .state('dashboard', {
                    url: '/',
                    templateUrl: 'ng/modules/dashboard/view.html',
                    controller: "dashboardController"
                });
    }]);