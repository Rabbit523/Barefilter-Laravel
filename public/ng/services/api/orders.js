application.factory("ordersAPI", ['apiRequest', function (apiRequest) {
    var extended = {};
    var endpoint = "orders";
    extended.getDashboard = function (startDate, endDate, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/dashboard/" + startDate + "/" + endDate, {}, onSuccess, onError);
    };

    extended.search = function (id, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/search/" + id , {}, onSuccess, onError);
    };
    extended.getHistory = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/history/" + uid, {}, onSuccess, onError);
    };
    extended.getTimeframedHistory = function (uid, sid, startDate, endDate, onSuccess, onError) {
        var url = endpoint + "/timeframed-history/" + uid + "/" + sid + "/" + startDate + "/" + endDate;
        apiRequest.ajax("GET", url, {}, onSuccess, onError);
    };
    extended.browseSubscriptions = function (startDate, endDate, onSuccess, onError) {
        var url = endpoint + "/browse-subscriptions/" + startDate + "/" + endDate;
        apiRequest.ajax("GET", url, {}, onSuccess, onError);
    };
    extended.getSubscriptionTypes = function (onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/subscription-types", {}, onSuccess, onError);
    };
    extended.getOneTimeTransactionsByUserId = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/one-time-transactions/" + uid, {}, onSuccess, onError);
    };
    extended.getSubscriptionsByUserId = function (uid, onSuccess, onError) {
        apiRequest.ajax("GET", endpoint + "/subscriptions/" + uid, {}, onSuccess, onError);
    };
    extended.transferSubscription = function (data, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/transfer-subscription", data, onSuccess, onError);
    };
    extended.cancelSubscription = function (data, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/cancel-subscription", data, onSuccess, onError);
    };
    extended.place = function (data, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/place", data, onSuccess, onError);
    };
    extended.delete = function (data, onSuccess, onError) {
        apiRequest.ajax("POST", endpoint + "/delete", data, onSuccess, onError);
    };

    extended.exportToExcel = function (uid, sid, startDate, endDate) {
        var url = endpoint + "/export-to-excel/" + uid + "/" + sid + "/" + startDate + "/" + endDate;
        window.location = config.server + url;
    };
    return extended;
}]);