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
    .controller('CropController', function ($scope, model, file, $mdDialog, $filter) {
        const vm = this;

        // We create a copy to work with, in case the user cancels the dialog
        const modelCopy = angular.copy(model);
        const fileCopy = angular.copy(file);

        vm.model = model;
        vm.file = file;

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
            angular.copy(modelCopy, model);
            angular.copy(fileCopy, file);
            $mdDialog.hide();
        };

        vm.generateRGB = (colorArray) => {
            if (typeof colorArray === 'undefined' || colorArray.length < 3) {
                return '';
            }
            return `rgb(${colorArray[0]}, ${colorArray[1]}, ${colorArray[2]})`;
        };

        vm.save = () => {

            console.log(modelCopy.cropObject.areaCoords.y);



            // Change the file name
            file.name = $filter('slugify')(file.name);

            $mdDialog.hide();
        };

});
