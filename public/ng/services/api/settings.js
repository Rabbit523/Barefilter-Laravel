application.factory("settingsAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "settings";
    extended.get = function (onSuccess, onError) {
        apiRequest.ajax("GET", endpoint, {}, onSuccess, onError);
    };

    extended.update = function (settings, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/update", settings, onSuccess, onError);
    };
    return extended;
}]);