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
    .directive('uploadFile', function (BASE_PATH, Upload) {
        return {
            restrict: 'E',
            templateUrl: BASE_PATH + 'admin/FileUpload',
            scope: {
                model: '='
            },
            link: function (scope) {

                scope.progress = 0;
                scope.show_progress = false;

                if(scope.model && scope.model.file_path) {
                    scope.file = scope.model.file_path;
                }

                // upload on file select or drop
                scope.upload = function (file) {

                    scope.show_progress = true;

                    Upload.upload({
                        url: 'admin/upload',
                        data: {
                            file: file
                        }
                    }).then(function (resp) {
                        scope.file = file;
                        scope.show_progress = false;
                        scope.model = resp.data.data;
                    }, function (resp) {
                        console.log('Error status: ' + resp.status);
                    }, function (evt) {
                        scope.progress = parseInt(100.0 * evt.loaded / evt.total);
                    });
                };

            }

        };
});
