'use strict';
var barefilterSearch = angular.module('barefilterSearch', []);
barefilterSearch.directive('barefilterSearch', [ function () {
    return {
        templateUrl: '/ng/directives/search/view.html',
        scope: {
            
        },
        controller: function ($scope) {
            $scope.results = [];
            $scope.categories = [];
            $scope.q = '';
            $scope.width = '';
            $scope.height = '';
            $scope.length = '';
            $scope.selectedCategory = '0';
            var cart = window.barefilterStore.getCartInstance();

            $scope.$watch('selectedCategory', function(newVal){
                search();
            });

            $scope.search = function(){
                search();
            };

            $scope.hasResults = function(){
                return $scope.results.length > 0;
            };
            $scope.addToCart = function(product){
                cart.add({
                    id: product.id,
                    name: product.name,
                    category: product.sku,
                    price: product.price,
                    image: (product.images.length > 0)? product.images[0].url : "",
                    amount: 1
                }, {id: 1, discount: 0});
            }

            var search = function(){
                Barefilter.API.advancedSearch(getSearchParams(), function(response){
                    if(response.success){
                        $scope.results = response.data.products.filter(function(p) {
                            return ($scope.selectedCategory !== '0') ? p.category_id === parseInt($scope.selectedCategory) : true;
                        });
                        $scope.$apply();
                    }
                });
            }

            var getSearchParams = function(){
                $scope.q, $scope.selectedCategory;
                var p = {q: $scope.q, width: $scope.width, height: $scope.height, length: $scope.length, category_id: $scope.selectedCategory};
                var params = {};
                for(var prop in p){
                    if(p.hasOwnProperty(prop) && p[prop] !== ''){
                        params[prop] = p[prop];
                    }
                }
                if(params.category_id === '0'){
                    delete params.category_id;
                }
                return params;
            }

            var loadCategories = function(){
                Barefilter.API.categories(function(response){
                    if(response.success){
                        $scope.categories = response.data;
                        $scope.categories.push({id: 0, name: 'Velg Kategori'});
                        $scope.$apply();
                    }
                });
            }

            var getParameterByName = function(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }
            var init = function(){
                $scope.q = getParameterByName('q');
                loadCategories();
            };
            init();
        }
    };
}]);