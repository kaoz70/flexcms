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
        return $resource(system.base_url + 'admin/language/:id', { id: '@id'}, {
            query: {
                isArray: false
            },
            update: {
                method: 'PUT'
            },
            reorder: {
                method: 'PUT'
            }
        });
    });

angular.module('app')
    .service('LanguageService', function($http, Request){

        this.reorder = function(list) {

            var order = [],
                data = {};

            angular.forEach(list, function(value) {
                order.push(value.id);
            });

            data.order = JSON.stringify(order);

            return Request.post(data, 'admin/language/reorder');

        };

});

