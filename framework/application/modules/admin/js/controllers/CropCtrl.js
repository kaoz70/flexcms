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
    .controller('CropCtrl', function($scope, file, $filter, $mdDialog, Color){

        $scope.file = file;

        //We create a copy to work with, in case the user cancels the dialog
        var fileCopy = angular.copy($scope.file);
        var modelCopy = angular.copy($scope.model);

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
            $mdDialog.hide();
        };

        //If the dominant color changes update the light/dark text
        $scope.$watch('model.colors.dominantColor', function(color) {
            $scope.model.colors.textColor = Color.isLight(color) ? 'dark' : 'light';
        });

        $scope.generateRGB = function (colorArray) {
            return 'rgb(' + colorArray[0] + ',' + colorArray[1] + ',' + colorArray[2] + ')';
        };

        $scope.save = function () {

            //Change the file name
            file.file_name = $filter('slugify')($scope.file.name);

            //Copy the models
            angular.copy(modelCopy, $scope.model);
            angular.copy(fileCopy, file);

            //Close the dialog
            $mdDialog.hide();

        };

});
