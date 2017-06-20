/**
 * @ngdoc resource
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Language', function($resource){
        return $resource(system.base_url + 'admin/language/:method/:id', { id: '@id'}, {
            query: {
                isArray: false
            },
            update: {
                method: 'PUT'
            }
        });
    });

