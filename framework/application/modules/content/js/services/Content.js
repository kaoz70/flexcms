/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Content', function($q, $http, $httpParamSerializer, Notification, Response){

        var urls = {
            create: 'admin/content/create/',
            update: 'admin/content/update/',
            delete: 'admin/content/delete/',
            insert: 'admin/content/insert',
            edit: 'admin/content/edit/'
        };

        this.edit = function(id) {
            return $http.get(urls.edit + id)
                .success(Response.validate)
                .error(Response.error);
        };

        this.delete = function(id) {
            return $http.delete(urls.delete + id)
                .success(function (response) {
                    if(Response.validate(response)) {
                        Notification.show('success', response.message);
                    }
                })
                .error(Response.error);
        };

        this.save = function(content, translations) {

            var data = {
                content: content,
                translations: translations
            };

            return $http({
                method: 'POST',
                url: urls.update + content.id,
                data: $httpParamSerializer(data),
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

