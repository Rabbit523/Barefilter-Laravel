'use strict';
admin.controller("pagesController", ["$rootScope", "$scope", "barefilterAPI",  function ($rootScope, $scope, barefilterAPI) {
    $rootScope.title = "Sider";
    $scope.loading = false;
    $scope.pages = [];

    $scope.hasPages = function(){
        return $scope.pages.length > 0;
    };

    var loadPages = function(){
        $scope.loading = true;
        barefilterAPI.content.all(function(pages){
            $scope.loading = false;
            $scope.pages = pages.map(function(p){
                p.created_at = new Date(p.created_at);
                p.updated_at = new Date(p.updated_at);
                return p;
            });
            $scope.$apply();
        }, function(){
            $scope.loading = false;
        });
    };
    var init = function () {
        loadPages();
    };
    init();
}]);