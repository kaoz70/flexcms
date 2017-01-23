/**
 * @ngdoc resource
 * @name app:Content
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('ImageConfig', function($resource){
        return $resource(system.base_url + 'admin/pages/images/:id', {id: '@id'}, {
            query: {
                isArray: false
            },
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
    })