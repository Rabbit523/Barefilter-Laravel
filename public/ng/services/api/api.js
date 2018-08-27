application.factory('barefilterAPI',
    ["usersAPI", "ordersAPI", "storesAPI", "buildingsAPI", "settingsAPI", "contentAPI",
        function (usersAPI, ordersAPI, storesAPI, buildingsAPI, settingsAPI, contentAPI) {
            var instance = {};
            instance.users = usersAPI;
            instance.orders = ordersAPI;
            instance.stores = storesAPI;
            instance.buildings = buildingsAPI;
            instance.settings = settingsAPI;
            instance.content = contentAPI;
            return instance;
        }]);