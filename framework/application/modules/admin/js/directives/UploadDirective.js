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
                model: '=',
            },
            link: function (scope, element, attrs) {



               /* var input = $(element[0].querySelector('#fileInput'));
                var button = $(element[0].querySelector('#uploadButton'));
                var textInput = $(element[0].querySelector('#textInput'));

                if (input.length && button.length && textInput.length) {
                    button.click(function(e) {
                        input.click();
                    });
                    textInput.click(function(e) {
                        input.click();
                    });
                }

                input.on('change', function(e) {
                    var files = e.target.files;
                    if (files[0]) {
                        scope.fileName = files[0].name;
                    } else {
                        scope.fileName = null;
                    }
                    scope.$apply();
                });


*/

                scope.progress = 0;
                scope.show_progress = false;

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
                        console.log('Success ' + resp.config.data.file.name + 'uploaded. Response: ' + resp.data);
                    }, function (resp) {
                        console.log('Error status: ' + resp.status);
                    }, function (evt) {
                        var progressPercentage = parseInt(100.0 * evt.loaded / evt.total);
                        scope.progress = progressPercentage;
                    });
                };

            }

        };
});
