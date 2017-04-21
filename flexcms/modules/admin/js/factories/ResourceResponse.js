/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('ResourceResponse', function ($q) {

        return {

            /**
             * Return a promise for a resource's query
             *
             * @param service
             */
            query(service) {
                const deferred = $q.defer();
                service.query(
                    (successData) => {
                        deferred.resolve(successData);
                    }, (errorData) => {
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
            get(service, params) {
                const deferred = $q.defer();
                service.get(params,
                    (successData) => {
                        deferred.resolve(successData);
                    }, (errorData) => {
                        deferred.reject(errorData);
                    });
                return deferred.promise;
            },

        };

});

