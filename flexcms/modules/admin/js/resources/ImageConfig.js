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
        return $resource(system.base_url + 'admin/imageConfig/:method/:page_id/:image_id', {id: '@id'}, {
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