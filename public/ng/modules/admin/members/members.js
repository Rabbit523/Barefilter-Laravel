'use strict';
admin.controller("membersController", ["$rootScope", "$scope", "$state", 'barefilterAPI', function ($rootScope, $scope, $state, barefilterAPI) {
    $rootScope.title = "Members";
    $scope.searchQuery = "";
    $scope.users = [];
    $scope.loading = false;

    var modal = "#create-product-modal";

    $scope.triggerSearch = function () {
        search();
    };

    $scope.hasMembers = function () {
        return $scope.users.length > 0;
    };

    $scope.getPhone = function (user) {
        return (user.phone !== "") ? user.phone : "No phone";
    };

    // $scope.$watch('searchQuery', function (val) {

    //     search();
    // });

    $scope.delete = function (user) {
        if (confirm("Are you sure you want to delete this member?")) {
            barefilterAPI.users.delete({ uid: user.id }, function (data) {
                search();
            }, function () { });
        }
    };

    $scope.addNew = function () {
        $scope.isAddingNew = true;
        $scope.useSame = true;
        $scope.member = getDefaultUser();
        $scope.shipping = getDefaultAddress();
        $scope.billing = getDefaultAddress();
        $(modal).modal('show');
    };

    $scope.save = function () {
        $(modal).modal('hide');
        var payload = {
            user: $scope.member,
            addresses: {
                same: ($scope.useSame),
                shipping: $scope.shipping,
                billing: ($scope.useSame) ? null : $scope.billing
            }
        };
        barefilterAPI.users.createMember(payload, function (data) {
            $state.go('member', { id: data.id });
        }, function () { });
    };

    var getDefaultUser = function () {
        return {
            first_name: "",
            last_name: "",
            email: "",
            phone: ""
        }
    };

    var getDefaultAddress = function () {
        return {
            first_name: "",
            last_name: "",
            email: "",
            phone: "",
            street_address: "",
            postal_code: "",
            city: ""
        }
    };

    var search = function () {
        if (!$scope.loading) {
            $scope.loading = true;
            barefilterAPI.users.searchMembers($scope.searchQuery, function (data) {
                $scope.loading = false;
                $scope.users = data;
                $scope.$apply();
            }, function () {

            });
        }else{
            setTimeout(search, 100);
        }

    };
    var init = function () {
        search();
    };
    init();
}]);