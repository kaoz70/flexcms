/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Request', function($http, $httpParamSerializer, Response, Notification){

        /**
         * Send a POST request to the server
         *
         * @param data
         * @param url
         */
        this.post = function (data, url) {
            return $http({
                method: 'POST',
                url: url,
                data: $httpParamSerializer(data),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (response) {
                    if(Response.validate(response)) {
                        Notification.show('success', response.message);
                    }
                })
                .error(Response.error);
        }

});

