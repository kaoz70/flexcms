/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Page', function($q, $http, $httpParamSerializer, Notification){

        var urls = {
            create: 'admin/page/create',
            update: 'admin/page/update/',
            delete: 'admin/page/delete/',
            insert: 'admin/page/insert',
            edit: 'admin/page/edit/'
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

                if (response.error_code) {
                    $('#modal-danger')
                        .modal('show')
                        .find('.modal-body')
                        .html(response.message);
                } else {
                    Notification.show('success', response.message);
                }

            }).error(function (data, status, headers, config) {
                $('#modal-danger')
                    .modal('show')
                    .find('.modal-body')
                    .html('Al parecer hay un error de servidor<br />[Error: ' + status + ']');
            });
        };

});

