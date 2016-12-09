/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Page', function($q, $http, $httpParamSerializer, Notification, Response){

        var urls = {
            create: 'admin/page/create',
            update: 'admin/page/update/',
            delete: 'admin/page/delete/',
            insert: 'admin/page/insert',
            edit: 'admin/page/edit/',
            pages: 'admin/page/all/'
        };

        this.getOne = function(id) {
            return $http.get(urls.edit + id)
                .success(Response.validate)
                .error(Response.error);
        };
        
        this.getAll = function (language) {
            return $http.get(urls.pages + language)
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

