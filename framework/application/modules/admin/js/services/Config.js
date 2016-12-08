/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Config', function($http, Request, Response){

        var urls = {
            index: 'admin/config',
            general: 'admin/config/general',
            social: 'admin/config/social/',
            save: 'admin/config/save'
        };

        this.getIndex = function() {
            return $http.get(urls.index)
                .success(Response.validate)
                .error(Response.error);
        };

        this.getGeneral = function() {
            return $http.get(urls.general)
                .success(Response.validate)
                .error(Response.error);
        };

        this.getSocial = function(id) {
            return $http.get(urls.edit + id)
                .success(Response.validate)
                .error(Response.error);
        };

        this.save = function(config) {
            return Request.post(config, urls.save);
        };

});

