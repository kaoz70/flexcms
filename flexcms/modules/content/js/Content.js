/**
 * @ngdoc resource
 * @name app:Content
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Content', ($resource) => $resource(`${system.base_url}admin/content/:method/:id`, { id: '@id' }, {
        query: {
            isArray: false,
        },
        save: {
            method: 'POST',
        },
        update: {
            method: 'PUT',
        },
        get: {
            method: 'GET',
        },
        delete: {
            method: 'DELETE',
        },
    }))
    .factory('ContentConfig', ($resource) => $resource(`${system.base_url}admin/content/config/:id`, { id: '@id' }, {
        update: {
            method: 'PUT',
        },
        get: {
            method: 'GET',
        },
    }));
