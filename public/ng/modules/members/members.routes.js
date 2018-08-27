'use strict';
members.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('orders', {
            url: '/orders',
            templateUrl: 'ng/modules/members/orders/view.html',
            controller: "ordersController"
        })
        .state('one-time-transactions', {
            url: '/one-time-transactions',
            templateUrl: 'ng/modules/members/one-time-transactions/view.html',
            controller: "oneTimeTransactionsController"
        })

        .state('subscriptions', {
            url: '/subscriptions',
            templateUrl: 'ng/modules/members/subscriptions/view.html',
            controller: "subscriptionsController"
        })
        .state('package-tracking', {
            url: '/package-tracking',
            templateUrl: 'ng/modules/members/package-tracking/view.html',
            controller: "packageTrackingController"
        })
        .state('member-settings', {
            url: '/member-settings',
            templateUrl: 'ng/modules/members/settings/view.html',
            controller: "memberSettingsController"
        })
        .state('partner', {
            url: '/partner',
            templateUrl: 'ng/modules/members/partner/view.html',
            controller: "partnerController"
        })
        .state('buildings', {
            url: '/buildings',
            templateUrl: 'ng/modules/members/buildings/view.html',
            controller: "buildingsController"
        })
        .state('building', {
            url: '/buildings/:id',
            templateUrl: 'ng/modules/members/building/view.html',
            controller: "buildingController"
        });
}]);