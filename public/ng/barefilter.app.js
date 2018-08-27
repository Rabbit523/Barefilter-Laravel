'use strict';
barefilterApp.run(['$rootScope', '$location', 'usersService',
    function ($rootScope, $location, usersService) {
        var path = "";
        $rootScope.$on('$locationChangeStart', function (event, next, current) {
            path = $location.path();
        });
        $rootScope.isBrowsing = function (_path) {
            return (_path === "dashboard" && path === "/") ? true : path.includes(_path);
        };
        $rootScope.isAdmin = function () {
            return usersService.isAdmin();
        };
        $rootScope.isPartner = function () {
            return usersService.isPartner();
        };
        $rootScope.isMember = function () {
            return usersService.isMember();
        };
    }]);