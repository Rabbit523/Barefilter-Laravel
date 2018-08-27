application.factory("buildingsAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "buildings";
    
    extended.profile = function (id, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/profile/" + id, {}, onSuccess, onError);
    };
    extended.mine = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/mine/" + uid, {}, onSuccess, onError);
    };
    extended.add = function (building, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/add", building, onSuccess, onError);
    };
    extended.addFacility = function (facility, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/add-facility", facility, onSuccess, onError);
    };
    return extended;
}]);