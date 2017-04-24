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
             */
            query(service) {
                const deferred = $q.defer();
                service.query((successData) => {
                    try {
                        JSON.parse(successData);
                        deferred.resolve(successData);
                    } catch (e) {
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
                    try {
                        JSON.parse(successData);
                        deferred.resolve(successData);
                    } catch (e) {
                        deferred.reject(formatErrorResponse(successData));
                    }
                }, (errorData) => {
                    deferred.reject(errorData);
                });
                return deferred.promise;
            },
        };
    });
