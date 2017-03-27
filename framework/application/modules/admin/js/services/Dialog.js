/**
 * @ngdoc service
 * @name app:Color
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Dialog', function($mdDialog, BASE_PATH, Response){
        
        function closeDialog() {
            $mdDialog.hide();
        }

        this.responseError = function(rejection) {

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                parent: angular.element(document.body),
                controller: function ($scope) {
                    $scope.message = rejection.statusText;
                    $scope.status = rejection.status;
                    $scope.showNotificationButton = true;
                    $scope.notified = false;
                    $scope.close = closeDialog;
                    $scope.notify = function () {
                        $scope.notified = true;
                        Response.notify(rejection);
                    };
                },
                clickOutsideToClose:true
            });

        };

        this.invalidResponseError = function(err, response) {

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                parent: angular.element(document.body),
                controller: function ($scope) {
                    $scope.message = err;
                    $scope.detail = response.data.data.message;
                    $scope.showNotificationButton = response.data.notify;
                    $scope.notified = false;
                    $scope.close = closeDialog;
                    $scope.notify = function () {
                        $scope.notified = true;
                        Response.notify(response.data);
                    };
                },
                clickOutsideToClose:true
            });

        };

});

