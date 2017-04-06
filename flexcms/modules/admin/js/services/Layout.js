/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Layout', function($q, $http, $httpParamSerializer, Notification, Response){

        var urls = {
            update: 'admin/layout/update/',
            delete: 'admin/layout/delete/',
            insert: 'admin/layout/insert',
            edit: 'admin/layout/edit/'
        };

        this.get = function(id) {
            return $http.get(urls.edit + id)
                .success(Response.validate)
                .error(Response.error);
        };
        
        this.save = function(language) {
            return $http({
                method: 'POST',
                url: urls.update + language.id,
                data: $httpParamSerializer(language),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                .success(function (response) {
                    if(Response.validate(response)) {
                        Notification.show('success', response.message);
                    }
                })
                .error(Response.error);
        };


});

