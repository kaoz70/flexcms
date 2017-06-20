/**
 * Created by Miguel on 06-Jan-17.
 */

/**
 * @ngdoc resource
 * @name App:Form
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Form', function($resource){
        return $resource(system.base_url + 'admin/forms/:id', { id: '@id'}, {
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
    });
