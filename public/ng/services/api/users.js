application.factory("usersAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "users";
    extended.searchMembers = function (q, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/members/" + q, {}, onSuccess, onError);
    };
    extended.searchPartners = function (q, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/partners/" + q, {}, onSuccess, onError);
    };

    extended.profile = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/profile/" + uid, {}, onSuccess, onError);
    };

    extended.createMember = function (payload, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/add-member", payload, onSuccess, onError);
    };

    extended.createPartner = function (payload, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/add-partner", payload, onSuccess, onError);
    };

    extended.update = function (user, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/update", user, onSuccess, onError);
    };
    extended.delete = function (data, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/delete", data, onSuccess, onError);
    };
    extended.changePassword = function (password, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/password", password, onSuccess, onError);
    };

    extended.myAddresses = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/addresses/" + uid, {}, onSuccess, onError);
    };
    extended.addAddress = function (address, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/add-address", address, onSuccess, onError);
    };
    extended.deleteAddress = function (address, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/delete-address", address, onSuccess, onError);
    };
    return extended;
}]);