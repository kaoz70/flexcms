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
        const formatErrorResponse = (response) => {

            // The error string is transformed into an Resource() object,
            // we need to transform it into a string again,
            // but it has no methods for this
            let string = '';
            angular.forEach(response, (prop) => {
                string += prop;
            });

            return {
                data: {
                    message: 'Not valid JSON data',
                    response: string,
                },
            };
        };

        return {

            /**
             * Return a promise for a resource's query
             *
             * @param service
             * @param params
             */
            query(service, params = {}) {
                const deferred = $q.defer();
                service.query(params, (successData) => {
                    if (successData.data) {
                        deferred.resolve(successData);
                    } else {
                        deferred.reject(formatErrorResponse(successData));
                    }
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
                service.get(params, (successData) => {
                    if (successData.data) {
                        deferred.resolve(successData);
                    } else {
                        deferred.reject(formatErrorResponse(successData));
                    }
                }, (errorData) => {
                    deferred.reject(errorData);
                });
                return deferred.promise;
            },
        };
    });
