/**
 * @ngdoc resource
 * @name app:LayoutResource
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('LayoutResource', function ($resource) {
        return $resource(system.base_url + 'admin/layout/:method/:id', { id: '@id' }, {
            query: {
                isArray: false,
            },
            update: {
                method: 'PUT',
            },
        });
    });

