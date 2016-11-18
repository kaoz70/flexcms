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
            update: 'admin/content/update/',
            delete: 'admin/content/delete/',
            edit: 'admin/content/edit/',
            config: 'admin/content/config/',
            config_save: 'admin/content/config_save/'
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

        this.getConfig = function (widget_id) {
            return $http.get(urls.config + widget_id)
                .success(Response.validate)
                .error(Response.error);
        };

        this.setConfig = function (widget_id, $scope) {

            var data = {
                config: $scope.config,
                translations: $scope.languages,
                page: $scope.page,
            };

            return $http({
                method: 'POST',
                url: urls.config_save + widget_id,
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

        this.save = function($scope) {

            var data = {
                content: $scope.content,
                translations: $scope.languages
            };

            return $http({
                method: 'POST',
                url: urls.update + $scope.content.id,
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

