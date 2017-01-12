/**
 * @ngdoc resource
 * @name app:Page
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Page', function($resource){
        return $resource(system.base_url + 'admin/pages/:id', { id: '@id'}, {
            query: {
                isArray: true
            }
        });
    });

