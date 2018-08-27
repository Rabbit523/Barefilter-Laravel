application.factory('usersService', ["$window",
    function ($window) {
        var instance = {};
        instance.isAdmin = function () {
            return (user !== null && user.role_id === 1);
        };
        instance.isPartner = function () {
            return (user !== null && user.role_id === 2);
        };
        instance.isMember = function () {
            return (user !== null && user.role_id === 3);
        };
        instance.getLoggedUser = function () {
            return user;
        };
        instance.getLoggedUserDiscount = function () {
            return parseInt(user.properties.discount);
        };
        instance.updateLoggedUser = function (u) {
            user = u;
        };
        instance.getLoggedUserId = function () {
            return user.id;
        };
        instance.getLoggedUserFullName = function () {
            return user.first_name + " " + user.last_name;
        };
        return instance;
    }]);