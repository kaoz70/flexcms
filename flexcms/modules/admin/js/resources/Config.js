/**
 * @ngdoc resource
 * @name app:Content
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Config', function($resource){
        return $resource(system.base_url + 'admin/config/:section', {section: '@section'}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
    })