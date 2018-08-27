application.factory("contentAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "content";
    
    extended.all = function (onSuccess, onError) {
        apiRequest.ajax("GET", endpoint, {}, onSuccess, onError);
    };

    extended.get = function (handle, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/" + handle, {}, onSuccess, onError);
    };
    extended.update = function (project, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/update", project, onSuccess, onError);
    };
    return extended;
}]);