'use strict';
members.directive('buildingsManager', ["usersService", "barefilterAPI", function (usersService, barefilterAPI) {
    return {
        templateUrl: 'ng/modules/members/partner/buildings.html',
        scope: {
        },
        controller: function ($scope) {

            var getDefaultFacilities = function () {
                return [{ id: 0, name: "Opprett avdelig" }];
            };
            var getDefaultBuilding = function () {
                var user = usersService.getLoggedUser();
                return {
                    uid: user.id,
                    name: "",
                    first_name: user.first_name,
                    last_name: user.last_name,
                    phone: user.phone,
                    email: user.email,
                    street_address: "",
                    postal_code: "",
                    city: ""
                };
            };
            var getDefaultFacility = function () {
                return {
                    name: "",
                    building_id: $scope.selectedBuildingId
                };
            }
            var loadBuildings = function () {
                barefilterAPI.buildings.mine(usersService.getLoggedUserId(), function (buildings) {
                    if(buildings.length > 0){
                        $scope.buildings = $scope.buildings.concat(buildings);
                        $scope.selectedBuildingId = buildings[0].id.toString();
                        $scope.selectedBuilding = buildings[0];
                        if($scope.selectedBuilding.facilities.length > 0){
                            $scope.facilities = $scope.facilities.concat($scope.selectedBuilding.facilities);
                            $scope.selectedFacilityId = $scope.selectedBuilding.facilities[0].id.toString();
                            notifyFacilitySelection($scope.selectedBuilding.facilities[0]);
                        }
                    }
                    $scope.$apply();
                }, function (err) { });
            };
            var init = function () {
                loadBuildings();
            };
            var notifyFacilitySelection = function(facility){
                $scope.$emit("facilitySelection", {building: $scope.selectedBuilding, facility: facility});
            };


            $scope.loading = false;
            $scope.buildings = [{ id: 0, name: "Opprett bygg" }];
            $scope.facilities = getDefaultFacilities();
            $scope.selectedBuildingId = -1;
            $scope.selectedFacilityId = -1;
            $scope.newBuilding = getDefaultBuilding();
            $scope.selectedBuilding = null;

            $scope.hasBuildings = function () {
                return $scope.buildings.length > 1;
            };
            $scope.hasFacilities = function () {
                return ($scope.selectedBuilding !== null) ? $scope.selectedBuilding.facilities.length > 0 : true;
            };
            $scope.hasSelectedBuilding = function(){
                return $scope.selectedBuilding !== null;
            };
            $scope.startBuildingCreation = function () {
                $scope.selectedBuildingId = "0";
                $scope.newBuilding = getDefaultBuilding();
            };
            $scope.startFacilityCreation = function () {
                $scope.selectedFacilityId = "0";
                $scope.newFacility = getDefaultFacility();
            };
            $scope.isCreatingBuilding = function () {
                return $scope.selectedBuildingId === "0";
            };
            $scope.isCreatingFacility = function () {
                return $scope.selectedFacilityId === "0";
            };
            $scope.onBuildingSelection = function () {
                if ($scope.selectedBuildingId === "0") {
                    $scope.selectedBuilding = null;
                    $scope.newBuilding = getDefaultBuilding();
                    notifyFacilitySelection(null);
                } else {
                    for (var i = 0; i < $scope.buildings.length; i++) {
                        if ($scope.buildings[i].id.toString() === $scope.selectedBuildingId) {
                            $scope.selectedBuilding = $scope.buildings[i];
                            $scope.facilities = getDefaultFacilities().concat($scope.selectedBuilding.facilities);
                            if($scope.selectedBuilding.facilities.length > 0){
                                $scope.selectedFacilityId = $scope.selectedBuilding.facilities[0].id.toString();
                                notifyFacilitySelection($scope.selectedBuilding.facilities[0]);
                            }else{
                                notifyFacilitySelection(null);
                            }
                            break;
                        }
                    }
                }
            };
            $scope.onFacilitySelection = function () {
                if ($scope.selectedFacilityId === "0") {
                    $scope.newFacility = getDefaultFacility();
                    notifyFacilitySelection(null);
                } else {
                    for (var i = 0; i < $scope.facilities.length; i++) {
                        if ($scope.facilities[i].id.toString() === $scope.selectedFacilityId) {
                            notifyFacilitySelection($scope.facilities[i]);
                            break;
                        }
                    }
                }
            };

            $scope.cancelFacilityCreation = function(){
                if($scope.selectedBuilding.facilities.length > 0){
                    $scope.selectedFacilityId = $scope.selectedBuilding.facilities[0].id.toString();
                    notifyFacilitySelection($scope.selectedBuilding.facilities[0]);
                }else{
                    notifyFacilitySelection(null);
                }
            };
            $scope.saveBuilding = function () {
                $scope.loading = true;
                barefilterAPI.buildings.add($scope.newBuilding, function (building) {
                    $scope.loading = false;
                    building.facilities = [];
                    $scope.selectedBuildingId = building.id.toString();
                    $scope.selectedBuilding = building;
                    $scope.buildings.push(building);
                    $scope.newFacility = getDefaultFacility();
                    $scope.$apply();
                }, function () { });
            };
            $scope.saveFacility = function () {
                $scope.loading = true;
                barefilterAPI.buildings.addFacility($scope.newFacility, function (facility) {
                    $scope.loading = false;
                    $scope.selectedBuilding.facilities.push(facility);
                    $scope.selectedFacilityId = facility.id.toString();
                    $scope.newFacility = getDefaultFacility();
                    $scope.facilities = getDefaultFacilities().concat($scope.selectedBuilding.facilities);
                    notifyFacilitySelection(facility);
                    $scope.$apply();
                }, function () { });
            };
            init();
        }
    };
}]);