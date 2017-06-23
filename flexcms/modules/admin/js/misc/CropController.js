/**
 * @ngdoc controller
 * @name App:CropController
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('CropController', function ($scope, $mdDialog, $filter) {
        const vm = this;

        // We create a copy to work with, in case the user cancels the dialog
        vm.model = {};
        vm.file = {};

        angular.copy($scope.model, vm.model);
        angular.copy($scope.file, vm.file);

        // Get the width and height from the first image configuration (should be the biggest one)
        vm.width = vm.model.configs[0].width;
        vm.height = vm.model.configs[0].height;

        if (vm.model.areaCoords) {
            vm.initialSize = {
                w: vm.model.areaCoords.w,
                h: vm.model.areaCoords.h,
            };
            vm.initialCoords = {
                x: vm.model.areaCoords.x,
                y: vm.model.areaCoords.y,
            };
        }

        vm.closeDialog = () => {
            $mdDialog.hide();
        };

        vm.generateRGB = (colorArray) => {
            if (typeof colorArray === 'undefined' || colorArray.length < 3) {
                return '';
            }
            return `rgb(${colorArray[0]}, ${colorArray[1]}, ${colorArray[2]})`;
        };

        vm.save = () => {
            // Change the file name
            vm.file.file_name = $filter('slugify')(vm.file.name);

            angular.copy(vm.model, $scope.model);
            angular.copy(vm.file, $scope.file);

            $mdDialog.hide($scope);
        };
    });
