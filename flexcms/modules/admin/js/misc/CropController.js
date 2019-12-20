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
    .controller('CropController', function ($scope, $mdDialog, $filter, config, file, Color) {
        const vm = this;
        const configCopy = angular.copy(config);
        const fileCopy = angular.copy(file);

        // We create a copy to work with, in case the user cancels the dialog
        vm.config = config;
        vm.file = file;
        vm.status = '';

        // Get the width and height from the first image configuration (should be the biggest one)
        vm.width = vm.config.items[0].width;
        vm.height = vm.config.items[0].height;

        if (vm.file.data.coords.areaCoords) {
            vm.initialSize = {
                w: vm.file.data.coords.areaCoords.w,
                h: vm.file.data.coords.areaCoords.h,
            };
            vm.initialCoords = {
                x: vm.file.data.coords.areaCoords.x,
                y: vm.file.data.coords.areaCoords.y,
            };
        }

        vm.closeDialog = () => {
            angular.copy(configCopy, vm.config);
            angular.copy(fileCopy, vm.file);
            $mdDialog.hide();
        };

        $scope.$watch('vm.file.data.coords', (coords) => {
            // Check if the cropped image will be pixelated
            if (coords) {
                if (coords.cropImageWidth < vm.width || coords.cropImageHeight < vm.height) {
                    vm.status = '<span class="crop-status md-warn-text"><i class="material-icons">warning</i> PIXELADO</span>';
                } else {
                    vm.status = '<span class="crop-status md-success-text"><i class="material-icons">check</i> OK</span>';
                }
            }
        });

        $scope.$watch('vm.file.name', (name) => {
            // Change the file name
            vm.file.file_name = $filter('slugify')(name);
        });

        $scope.$watch('vm.file.data.colors.dominantColor', (color) => {
            vm.file.data.colors.textColor = Color.isLight(color) ? 'dark' : 'light';
        });

        // Generate the CSS color from the array
        vm.generateRGB = (colorArray) => {
            if (typeof colorArray === 'undefined' || colorArray.length < 3) {
                return '';
            }
            return `rgb(${colorArray[0]}, ${colorArray[1]}, ${colorArray[2]})`;
        };

        vm.save = () => {
            $mdDialog.hide();
        };
    });
