/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Response', function(Notification, $httpParamSerializer, BASE_PATH, $document, $injector){

        var closeHandler = function () {
            $mdDialog.hide();
        };
        
        this.notify = function (data) {

            var $http = $injector.get('$http');
            
            $http({
                method: 'POST',
                url: 'admin/notifyError',
                data: $httpParamSerializer(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }, function (response) {

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

            }, Response.error);

        };

        /**
         * Validate a correct response with no errors
         *
         * @param responseData
         * @returns {*}
         */
        this.validate = function(response) {

            var responseData = response.data;

            //Is it a successful response?
            if (response.headers('Content-Type') === "application/json; charset=utf-8" && !responseData.success) {
                throw responseData.message ? responseData.message : "Hubo un problema con la petición";
            }

            //Is it a valid JSON response?
            else if(response.headers('Content-Type') === "application/json; charset=utf-8" && (responseData === undefined || typeof responseData === "string")) {
                throw "Hubo un problema con la petición";
            }

            return true;

        };

        this.error = function (data, status) {

            var $this = this;

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
                        $this.notify(data);
                    };
                },
                clickOutsideToClose:true
            });

        }

});

