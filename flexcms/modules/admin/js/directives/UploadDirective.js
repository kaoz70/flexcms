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
    .directive('uploadFile', function (BASE_PATH, Upload, $mdDialog, Color, Dialog) {

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
                    scope.model.colors.textColor = Color.isLight(color) ? 'dark' : 'light';
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
                    Dialog.delete('Est&aacute; seguro de que quiere eliminar &eacute;sta imagen?', scope.model.files, [file]);
                };

                scope.edit = function (file, evt) {

                    var template = scope.model.items[0].crop ?
                        BASE_PATH + '/admin/dialogs/ImageCrop' :
                        BASE_PATH + '/admin/dialogs/ImageEdit';

                    $mdDialog.show({
                        controller: 'CropCtrl',
                        controllerAs: 'ctrl',
                        templateUrl: template,
                        hasBackdrop: true,
                        locals : {
                            model: scope.model,
                            file: file
                        },
                        targetEvent: evt
                    }).then(function (model) {
                        console.log(model.cropObject.cropTop);
                    });

                };

            }

        };
    })
;
