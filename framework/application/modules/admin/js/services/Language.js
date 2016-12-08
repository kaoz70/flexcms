/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Language', function($http, Request, Response){

        var urls = {
            index: 'admin/language',
            update: 'admin/language/update/',
            delete: 'admin/language/delete',
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
            return Request.post(language, urls.update + language.id);
        };

        this.insert = function(language) {
            return Request.post(language, urls.insert);
        };

        this.delete = function(ids) {
            return Request.post(ids, urls.delete);
        };

});

