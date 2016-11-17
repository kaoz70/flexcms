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
            return $http.get(urls.index);
        };

        this.getOne = function(id) {
            return $http.get(urls.edit + id);
        };

        this.save = function(language) {
            return $http({
                method: 'POST',
                url: urls.update + language.id,
                data: $httpParamSerializer(language),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).success(function (response) {
                Response.validate(response);
            }).error(function (data, status, headers, config) {
                $('#modal-danger')
                    .modal('show')
                    .find('.modal-body')
                    .html('Al parecer hay un error de servidor<br />[Error: ' + status + ']');
            });
        };

});

