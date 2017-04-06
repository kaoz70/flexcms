/**
 * @ngdoc controller
 * @name App:LanguageCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('ErrorCtrl', function($scope, $mdDialog, error, BASE_PATH){

        var closeHandler = function () {
            $mdDialog.hide();
        };

        $mdDialog.show({
            templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
            parent: angular.element(document.body),
            controller: function ($scope) {

                var errorDetail = error.data.error;

                $scope.message = error.statusText;
                $scope.status = error.status;
                $scope.detail = errorDetail ? errorDetail.message : error.config.url;

                if(errorDetail) {
                    $scope.file = errorDetail.file;
                    $scope.line = errorDetail.line;
                }

                $scope.close = closeHandler;

            },
            clickOutsideToClose:true
        });

    })

;