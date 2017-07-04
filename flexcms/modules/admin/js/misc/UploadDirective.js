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
    .directive('uploadFile', function (BASE_PATH, Upload, $mdDialog, Color, Dialog, $timeout) {
        return {
            restrict: 'E',
            templateUrl: `${BASE_PATH}admin/FileUpload`,
            scope: {
                model: '=',
                existingFiles: '=',
                multiple: '=',
                onlyUpload: '=?',
            },
            link: (scope) => {
                if (typeof scope.onlyUpload === 'undefined') {
                    scope.onlyUpload = false;
                }

                if (scope.onlyUpload !== true) {
                    const firstConfig = scope.model.configs[0];

                    scope.resultImageSize = {
                        w: firstConfig.width,
                        h: firstConfig.height,
                    };

                    // FIXME: file not being updated
                    scope.edit = (file, evt) => {
                        const template = firstConfig.crop ?
                            `${BASE_PATH}/admin/dialogs/ImageCrop` :
                            `${BASE_PATH}/admin/dialogs/ImageEdit`;

                        $mdDialog.show({
                            controller: 'CropController',
                            controllerAs: 'vm',
                            templateUrl: template,
                            hasBackdrop: true,
                            scope: scope.$new(),
                            locals: {
                                config: scope.model,
                                file,
                            },
                            targetEvent: evt,
                        }).then(() => {
                            //console.log(config);
                            //angular.copy(scope.model, config);
                        });
                    };
                }

                scope.progress = 0;
                scope.configs = scope.model.configs;
                scope.show_progress = false;
                scope.model.files = scope.existingFiles ? scope.existingFiles : [];
                scope.columnWidth = scope.multiple ? 33 : 100;
                scope.model.colors = {
                    dominantColor: [],
                    paletteColor: [],
                    textColor: null,
                };

                const multiple = scope.multiple;

                scope.$watch('model.colors.dominantColor', (color) => {
                    scope.model.colors.textColor = Color.isLight(color) ? 'dark' : 'light';
                });

                // upload on file select or drop
                scope.upload = (files) => {
                    if (files && files.length) {
                        scope.show_progress = true;

                        Upload.upload({
                            url: 'admin/upload',
                            data: {
                                files,
                            },
                        }).then((resp) => {
                            if (multiple) {
                                scope.model.files = scope.model.files.concat(resp.data.data);
                            } else {
                                scope.model.files = resp.data.data;
                            }

                            scope.show_progress = false;
                        }, (resp) => {
                            console.log(`Error status: ${resp.status}`);
                        }, (evt) => {
                            scope.progress = parseInt((100.0 * evt.loaded) / evt.total, 10);
                        });
                    }
                };

                scope.onChangeHandler = ($dataURI) => {
                    console.log('chencge');
                };

                // TODO: delete the file server side
                scope.delete = (file) => {
                    Dialog.deleteFromList('Est&aacute; seguro de que quiere eliminar &eacute;sta imagen?', scope.model.files, [file]);
                };

            },

        };
    })
;
