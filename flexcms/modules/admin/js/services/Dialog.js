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

        /**
         * Shows a delete dialog, and on accept, removes the item from the array
         *
         * @param message
         * @param list
         * @param item
         * @param callback
         */
        this.delete = function (message, list, item, callback) {

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/dialogs/DeleteDialog',
                parent: angular.element(document.body),
                controller: function ($scope) {

                    $scope.message = message;

                    $scope.cancel = function () {
                        $mdDialog.hide();
                    };

                    $scope.delete = function () {
                        var index = list.indexOf(item);
                        list.splice(index, 1);
                        $mdDialog.hide();
                    };

                },
                clickOutsideToClose:true
            }).then(callback);

        }

});

