/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Widget', function($http, Request, Response){

        var urls = {
            edit: 'admin/widget/edit/',
            update: 'admin/widget/update/',
            delete: 'admin/widget/delete',
            insert: 'admin/widget/insert'
        };

        this.get = function(id) {
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

