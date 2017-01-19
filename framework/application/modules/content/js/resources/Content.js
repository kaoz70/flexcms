/**
 * @ngdoc resource
 * @name app:Content
 *
 * @description
 *
 *
 * */
angular.module('app')
    .factory('Content', function($resource){
        return $resource(system.base_url + 'admin/content/:id', { id: '@id'}, {
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
    })
    .factory('ContentConfig', function($resource){
        return $resource(system.base_url + 'admin/content/config/:id', { id: '@id'}, {
            update: {
                method: 'PUT'
            },
            get: {
                method: 'GET'
            }
        });
    })

    
    .service('ContentService', function($http, Request) {

        var urls = {
            reorder: 'admin/content/reorder/'
        };

        this.setOrder = function (list, page_id) {

            var order = [],
                data = {};

            angular.forEach(list, function (value) {
                order.push(value.id);
            });

            data.order = JSON.stringify(order);


            return Request.post(data, urls.reorder + page_id);

        };

    });