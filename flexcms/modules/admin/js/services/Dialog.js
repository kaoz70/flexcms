/**
 * @ngdoc service
 * @name app:Dialog
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Dialog', function ($mdDialog, BASE_PATH, Response, $document) {
        function closeDialog() {
            $mdDialog.hide();
        }

        this.responseError = (rejection) => {
            $mdDialog.show({
                templateUrl: `${BASE_PATH}admin/dialogs/ErrorDialog`,
                parent: angular.element($document[0].body),
                controller($scope) {
                    let data;

                    if (rejection.data.error) {
                        data = rejection.data.error;
                    } else {
                        data = rejection.data;
                    }

                    $scope.message = rejection.statusText;
                    $scope.detail = data.message;
                    $scope.status = rejection.status;
                    $scope.showNotificationButton = true;
                    $scope.notified = false;
                    $scope.close = closeDialog;
                    $scope.notify = () => {
                        $scope.notified = true;
                        Response.notify(rejection);
                    };
                },
                clickOutsideToClose: true,
            });
        };

        this.invalidResponseError = (err, response) => {
            $mdDialog.show({
                templateUrl: `${BASE_PATH}admin/dialogs/ErrorDialog`,
                parent: angular.element($document[0].body),
                controller($scope) {
                    $scope.message = err;
                    $scope.detail = response.data.data.message;
                    $scope.showNotificationButton = response.data.notify;
                    $scope.notified = false;
                    $scope.close = closeDialog;
                    $scope.notify = () => {
                        $scope.notified = true;
                        Response.notify(response.data);
                    };
                },
                clickOutsideToClose: true,
            });
        };

        this.delete = (message, ev, callback) => {
            $mdDialog.show({
                templateUrl: `${BASE_PATH}admin/dialogs/DeleteDialog`,
                parent: angular.element($document[0].body),
                targetEvent: ev,
                controller($scope) {
                    $scope.message = message;

                    $scope.cancel = () => {
                        $mdDialog.hide();
                    };

                    $scope.delete = () => {
                        callback();
                        $mdDialog.hide();
                    };
                },
                clickOutsideToClose: true,
            });
        };

        /**
         * Shows a delete dialog, and on accept, removes the item from the array
         *
         * @param message
         * @param list
         * @param items
         * @param callback
         */
        this.deleteFromList = (message, list, items, callback) => {
            $mdDialog.show({
                templateUrl: `${BASE_PATH}admin/dialogs/DeleteDialog`,
                parent: angular.element($document[0].body),
                controller($scope) {
                    $scope.message = message;

                    $scope.cancel = () => {
                        $mdDialog.hide();
                    };

                    $scope.delete = () => {
                        angular.forEach(items, (item) => {
                            const index = list.indexOf(item);
                            list.splice(index, 1);
                        });

                        $mdDialog.hide();
                    };
                },
                clickOutsideToClose: true,
            }).then(callback);
        };
    });
