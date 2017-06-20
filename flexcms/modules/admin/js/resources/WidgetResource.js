/**
 * @ngdoc resource
 * @name app:WidgetResource
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('WidgetResource', function ($resource) {
        return $resource(system.base_url + 'admin/widget/:method/:id', { id: '@id' }, {
            query: {
                isArray: false,
            },
            update: {
                method: 'PUT',
            },
        });
    });
