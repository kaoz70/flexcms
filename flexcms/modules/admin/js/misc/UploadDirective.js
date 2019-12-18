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
    .directive('uploadFile', (BASE_PATH, Upload, $mdDialog, Color, Dialog) => ({
        restrict: 'E',
        templateUrl: `${BASE_PATH}admin/FileUpload`,
        scope: {
            imageSection: '=',
            onlyUpload: '=?',
        },
        link: (scope) => {
            if (typeof scope.onlyUpload === 'undefined') {
                scope.onlyUpload = false;
            }

            if (scope.onlyUpload !== true) {
                const firstConfig = scope.imageSection.items[0];

                scope.resultImageSize = {
                    w: firstConfig.width,
                    h: firstConfig.height,
                };

                // FIXME: file not being updated
                scope.edit = (file, evt) => {
                    const template = firstConfig.crop
                        ? `${BASE_PATH}/admin/dialogs/ImageCrop`
                        : `${BASE_PATH}/admin/dialogs/ImageEdit`;

                    $mdDialog.show({
                        controller: 'CropController',
                        controllerAs: 'vm',
                        templateUrl: template,
                        hasBackdrop: true,
                        scope: scope.$new(),
                        locals: {
                            config: scope.imageSection,
                            file,
                        },
                        targetEvent: evt,
                    });
                };
            }

            scope.progress = 0;
            scope.configs = scope.imageSection.items;
            scope.show_progress = false;
            scope.imageSection.delete = [];
            scope.columnWidth = scope.imageSection.multiple_upload ? 33 : 100;
            scope.imageSection.colors = {
                dominantColor: [],
                paletteColor: [],
                textColor: null,
            };

            const { multiple } = scope;

            scope.$watch('model.colors.dominantColor', (color) => {
                scope.imageSection.colors.textColor = Color.isLight(color) ? 'dark' : 'light';
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
                            scope.imageSection.files = scope.imageSection.files.concat(resp.data.data);
                        } else {
                            scope.imageSection.files = resp.data.data;
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
                console.log('change');
            };

            scope.delete = (file) => {
                Dialog.deleteFromList('Est&aacute; seguro de que quiere eliminar &eacute;sta imagen?', scope.imageSection.files, [file], () => {
                    scope.imageSection.delete.push(file);
                });
            };
        },

    }));
