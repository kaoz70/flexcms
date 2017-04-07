/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('ResourceResponse', function($q){

        return {

            /**
             * Return a promise for a resource's query
             *
             * @param service
             */
            query: function (service) {

                var deferred = $q.defer();
                service.query(
                    function(successData) {
                        deferred.resolve(successData);
                    }, function(errorData) {
                        deferred.reject(errorData);
                    });
                return deferred.promise;

            },

            /**
             * Get a resource
             *
             * @param service
             * @param params
             */
            get: function (service, params) {

                var deferred = $q.defer();
                service.get(params,
                    function(successData) {
                        deferred.resolve(successData);
                    }, function(errorData) {
                        deferred.reject(errorData);
                    });
                return deferred.promise;

            }

        };

});

