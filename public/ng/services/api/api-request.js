application.factory('apiRequest', [function () {
    var instance = {};
    instance.ajax = function(type, endpoint, data, onSuccess, onError){
        $.ajax({
            type: type,
            url: config.server + endpoint,
            data: data,
            beforeSend: function (request) {
                request.setRequestHeader("X-CSRF-TOKEN", config.token);
            },
            success: function(response){
                if(response.success){
                    onSuccess(response.data);
                }else{
                    onError(response.error);
                }
            },
            error : onError
        });
    };
    return instance;
}]);