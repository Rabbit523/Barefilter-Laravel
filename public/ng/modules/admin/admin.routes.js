'use strict';
admin.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('sales', {
            url: '/sales',
            templateUrl: 'ng/modules/admin/sales/view.html',
            controller: "salesController"
        })
        .state('browse-subscriptions', {
            url: '/browse-subscriptions',
            templateUrl: 'ng/modules/admin/subscriptions/view.html',
            controller: "subscriptionsBrowserController"
        })    
        .state('members', {
            url: '/members',
            templateUrl: 'ng/modules/admin/members/view.html',
            controller: "membersController"
        })
        .state('member', {
            url: '/members/:id/profile',
            templateUrl: 'ng/modules/admin/member/view.html',
            controller: "memberController"
        })
        .state('partners', {
            url: '/partners',
            templateUrl: 'ng/modules/admin/partners/view.html',
            controller: "partnersController"
        })
        .state('profile', {
            url: '/:id/profile',
            templateUrl: 'ng/modules/admin/profile/view.html',
            controller: "profileController"
        })
        .state('stores', {
            url: '/stores',
            templateUrl: 'ng/modules/admin/stores/view.html',
            controller: "storesController"
        })
        .state('discounts', {
            url: '/discounts',
            templateUrl: 'ng/modules/admin/discounts/view.html',
            controller: "discountsController"
        })
        .state('categories', {
            url: '/categories',
            templateUrl: 'ng/modules/admin/categories/view.html',
            controller: "categoriesController"
        })
        .state('products', {
            url: '/products',
            templateUrl: 'ng/modules/admin/products/view.html',
            controller: "productsController"
        })
        .state('pages', {
            url: '/pages',
            templateUrl: 'ng/modules/admin/pages/view.html',
            controller: "pagesController"
        })
        .state('page', {
            url: '/pages/:handle/edit',
            templateUrl: 'ng/modules/admin/page/view.html',
            controller: "pageController"
        })
        .state('settings', {
            url: '/settings',
            templateUrl: 'ng/modules/admin/settings/view.html',
            controller: "settingsController"
        });
}]);