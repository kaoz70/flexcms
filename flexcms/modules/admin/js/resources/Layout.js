/**
 * @ngdoc resource
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Layout', function($resource){
        return $resource(system.base_url + 'admin/layout/:method/:id', { id: '@id'}, {
            query: {
                isArray: false
            },
            update: {
                method: 'PUT'
            }
        });
    });

