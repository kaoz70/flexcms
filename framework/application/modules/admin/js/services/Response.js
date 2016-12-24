/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Response', function($mdDialog, Notification, $http, $httpParamSerializer, BASE_PATH, $document){

        var closeHandler = function () {
            $mdDialog.hide();
        };
        
        var notifyHandler = function (data) {
            
            $http({
                method: 'POST',
                url: 'admin/notifyError',
                data: $httpParamSerializer(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (response) {

                    var view = 'SuccessDialog';

                    if(response.type == 'warning') {
                        view = 'WarningDialog';
                    }

                    $mdDialog.show({
                        templateUrl: BASE_PATH + 'admin/dialogs/' + view,
                        parent: angular.element(document.body),
                        controller: function ($scope) {
                            $scope.message = response.message;
                            $scope.close = closeHandler;
                        },
                        clickOutsideToClose:true
                    });

                })
                .error(Response.error);

        };

        /**
         * Validate a correct response with no errors
         *
         * @param response
         * @returns {*}
         */
        this.validate = function(response) {

            try {

                //Is it a successful response?
                if (!response.success) {
                    throw response.message;
                }

                //Is it a valid JSON response?
                else if(response == undefined || typeof response == "string") {
                    throw "Hubo un problema con la petición";
                }

                return response;

            } catch (err) {

                $mdDialog.show({
                    templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                    parent: angular.element(document.body),
                    controller: function ($scope) {
                        $scope.message = err;
                        $scope.detail = response.data.message;
                        $scope.showNotificationButton = response.notify;
                        $scope.close = closeHandler;
                        $scope.notify = function () {
                            notifyHandler(response.data);
                        };
                    },
                    clickOutsideToClose:true
                });

            }

            return false;

        };

        this.error = function (data, status) {

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/dialogs/ErrorDialog',
                parent: $document[0].body,
                controller: function ($scope) {
                    $scope.message = 'Error de servidor<br />[Error: ' + status + ']';
                    $scope.detail = data.error.message;
                    $scope.file = data.error.file;
                    $scope.line = data.error.line;
                    $scope.close = closeHandler;
                    $scope.showNotificationButton = true;
                    $scope.notify = function () {
                        notifyHandler(data);
                    };
                },
                clickOutsideToClose:true
            });

        }

});

