'use strict';
admin.controller("pageController", ["$rootScope", "$scope", "$stateParams", "barefilterAPI", function ($rootScope, $scope, $stateParams, barefilterAPI) {
    $rootScope.title = "Rediger side";
    $scope.page = {};
    $scope.loading = false;
    $scope.updating = false;
    $scope.sections = [];
    $scope.section = undefined;
    var content = {}, pageSection = "#page-section";
    $scope.update = function(){
        $scope.updating = true;
        $scope.page.content = parseSections();
        if($scope.page.content !== '{}'){
            barefilterAPI.content.update($scope.page, function(page){
                $scope.updating = false;
                $scope.$apply();
            }, function(){
                $scope.updating = false;
                $scope.$apply();
            });
        }
    };

    $scope.hasSections = function(){
        return $scope.sections.length > 0;
    };
    $scope.isEditingSection = function(section){
        return $scope.section === section;
    };
    $scope.editSection = function(section){
        if($scope.section !== undefined){
            $scope.section.text = $(pageSection).summernote('code');
        }
        $scope.section = section;
        $(pageSection).summernote('code', section.text);
    };

    var saveCurrentSection = function(){
        $scope.section.text = $(pageSection).summernote('code');
    };

    var parseSections = function(){
        saveCurrentSection();
        for(var prop in content){
            if(content.hasOwnProperty(prop)){
                content[prop] = findSectionById(prop);
            }
        }
        return JSON.stringify(content);
    };

    var findSectionById = function(id){
        var section;
        $scope.sections.forEach(function(sec){
            if(sec.id === id){
                section = sec;
                delete section.id;
            }
        });
        return section;
    }

    var parseContent = function(_content){
        var sections = [];
        content = JSON.parse(_content);
        for(var prop in content){
            if(content.hasOwnProperty(prop)){
                sections.push({
                    id: prop,
                    title: content[prop].title,
                    html: content[prop].html,
                    text: content[prop].text
                });
            }
        }
        
        return sections;
    }

    var loadPage = function(){
        $scope.loading = true;
        barefilterAPI.content.get($stateParams.handle, function(page){
            $scope.sections = parseContent(page.content);
            $scope.page = page;
            $scope.loading = false;
            $scope.$apply();
            setTimeout(initSummernote, 100);
        }, function(){
            $scope.loading = false;
            $scope.$apply();
        });
    };

    var initSummernote = function(){
        $(pageSection).summernote(getSummernoteSettings(400));
        if($scope.sections.length > 0){
            $scope.editSection($scope.sections[0]);
        }
        
    };

    var getSummernoteSettings = function (height) {
        return {
            height: height,
            onfocus: function (e) {
                $('body').addClass('overlay-disabled');
            },
            onblur: function (e) {
                $('body').removeClass('overlay-disabled');
            }
        }
    };

    var init = function () {
        loadPage();
        
    };
    init();
}]);