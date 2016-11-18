/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Language', function($q, $http, $httpParamSerializer, Notification, Response){

        var urls = {
            index: 'admin/language',
            create: 'admin/language/create',
            update: 'admin/language/update/',
            delete: 'admin/language/delete/',
            insert: 'admin/language/insert',
            edit: 'admin/language/edit/'
        };

        this.getAll = function() {
            return $http.get(urls.index)
                .success(Response.validate)
                .error(Response.error);
        };

        this.getOne = function(id) {
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

