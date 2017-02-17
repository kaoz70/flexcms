/**
 * @ngdoc directive
 * @name app:EdtorDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('uploadFile', function (BASE_PATH, Upload, $mdDialog, $filter) {

        /**
         * Check if the background is dark or light
         *
         * @link http://stackoverflow.com/questions/1855884/determine-font-color-based-on-background-color/1855903#1855903
         * @param colorArray
         * @returns {boolean}
         */
        var colourIsLight = function (colorArray) {

            if(!colorArray || colorArray.length !== 3) {
                return;
            }

            var r = colorArray[0],
                g = colorArray[1],
                b = colorArray[2];

            // Counting the perceptive luminance
            // human eye favors green color...
            var a = 1 - (0.299 * r + 0.587 * g + 0.114 * b) / 255;
            return (a < 0.5);
        };

        return {
            restrict: 'E',
            templateUrl: BASE_PATH + 'admin/FileUpload',
            scope: {
                model: '=',
                existingFiles: '=',
                multiple: '='
            },
            link: function (scope) {

                scope.progress = 0;
                scope.items = scope.model.items;
                scope.show_progress = false;
                scope.model.files = scope.existingFiles ? scope.existingFiles : [];
                scope.columnWidth = scope.multiple ? 33 : 100;
                scope.model.colors = {
                    dominantColor: [],
                    paletteColor: [],
                    textColor: null
                };

                scope.resultImageSize = {
                    w: scope.items[0].width,
                    h: scope.items[0].height
                };

                var multiple = scope.multiple;

                scope.$watch('model.colors.dominantColor', function(color) {
                    scope.model.colors.textColor = colourIsLight(color) ? 'dark' : 'light';
                });

                // upload on file select or drop
                scope.upload = function (files) {

                    if (files && files.length) {

                        scope.show_progress = true;

                        Upload.upload({
                            url: 'admin/upload',
                            data: {
                                files: files
                            }
                        }).then(function (resp) {

                            if(multiple) {
                                scope.model.files = scope.model.files.concat(resp.data.data);
                            } else {
                                scope.model.files = resp.data.data;
                            }

                            scope.show_progress = false;

                        }, function (resp) {
                            console.log('Error status: ' + resp.status);
                        }, function (evt) {
                            scope.progress = parseInt(100.0 * evt.loaded / evt.total);
                        });
                    }
                };

                //TODO: delete the file server side
                scope.delete = function (file) {

                    $mdDialog.show({
                        templateUrl: BASE_PATH + 'admin/dialogs/DeleteDialog',
                        parent: angular.element(document.body),
                        controller: function ($scope) {

                            $scope.message = 'Est&aacute; seguro de que quiere eliminar &eacute;sta imagen?';

                            $scope.cancel = function () {
                                $mdDialog.hide();
                            };

                            $scope.delete = function () {
                                var index = scope.model.files.indexOf(file);
                                scope.model.files.splice(index, 1);
                                $mdDialog.hide();
                            };

                        },
                        clickOutsideToClose:true
                    });

                };

                scope.edit = function (file, evt) {

                    var template = scope.model.items[0].crop ?
                        BASE_PATH + '/admin/dialogs/ImageCrop' :
                        BASE_PATH + '/admin/dialogs/ImageEdit';

                    $mdDialog.show({
                        controller: function ($scope) {

                            //We create a copy to work with, in case the user cancels the dialog
                            var copyModel = angular.copy(scope.model),
                                fileCopy = angular.copy(file);

                            $scope.file = fileCopy;
                            $scope.model = copyModel;

                            //Get the width and height from the first image configuration (should be the biggest one)
                            $scope.width = $scope.model.items[0].width;
                            $scope.height = $scope.model.items[0].height;

                            $scope.resultImageSize = scope.resultImageSize;

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
                                $scope.model.colors.textColor = colourIsLight(color) ? 'dark' : 'light';
                            });

                            $scope.generateRGB = function (colorArray) {
                                return 'rgb(' + colorArray[0] + ',' + colorArray[1] + ',' + colorArray[2] + ')';
                            };

                            $scope.save = function () {
                                angular.copy(copyModel, scope.model);
                                angular.copy(fileCopy, file);
                                file.file_name = $filter('slugify')($scope.file.name);
                                $mdDialog.hide();
                            };

                        },
                        controllerAs: 'ctrl',
                        templateUrl: template,
                        hasBackdrop: true,
                        targetEvent: evt
                    });

                };

            }

        };
});
