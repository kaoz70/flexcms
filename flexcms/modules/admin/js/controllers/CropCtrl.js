/**
 * @ngdoc controller
 * @name App:CropCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('CropCtrl', function($scope, model, file, $mdDialog, $filter){

        //We create a copy to work with, in case the user cancels the dialog
        var modelCopy = angular.copy(model);
        var fileCopy = angular.copy(file);

        $scope.model = model;
        $scope.file = file;

        //Get the width and height from the first image configuration (should be the biggest one)
        $scope.width = $scope.model.items[0].width;
        $scope.height = $scope.model.items[0].height;
        
        if($scope.model.areaCoords) {
            $scope.initialSize = {
                w: $scope.model.areaCoords.w,
                h: $scope.model.areaCoords.h
            };
            $scope.initialCoords = {
                x: $scope.model.areaCoords.x,
                y: $scope.model.areaCoords.y
            };
        }

        $scope.closeDialog = function () {
            angular.copy(modelCopy, model);
            angular.copy(fileCopy, file);
            $mdDialog.hide();
        };

        $scope.generateRGB = function (colorArray) {
            if(typeof colorArray === 'undefined' || colorArray.length < 3 ) {
                return;
            }
            return 'rgb(' + colorArray[0] + ',' + colorArray[1] + ',' + colorArray[2] + ')';
        };

        $scope.save = function () {

            /*angular.copy(modelCopy, model);
            angular.copy(fileCopy, file);*/

            //Change the file name
            file.name = $filter('slugify')(file.name);

            var obj = {
                modelCopy: modelCopy.cropObject,
                fileCopy: fileCopy
            };

            $mdDialog.hide(obj);
        };

});
