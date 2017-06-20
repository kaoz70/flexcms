/**
 * @ngdoc resource
 * @name app:Content
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('User', function($resource){
        return $resource(system.base_url + 'admin/auth/:method/:id', { id: '@id'}, {
            query: {
                isArray: false
            },
            save: {
                method: 'POST'
            },
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            },
            delete: {
                method: 'DELETE'
            }
        });
    })
    .factory('UserConfig', function($resource){
        return $resource(system.base_url + 'admin/auth/config/:id', { id: '@id'}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
    })

    ;