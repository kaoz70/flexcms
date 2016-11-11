/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Content', function($q, $http, $httpParamSerializer, Notification){

        var urls = {
            create: 'admin/content/create',
            update: 'admin/content/update/',
            delete: 'admin/content/delete/',
            insert: 'admin/content/insert',
            edit: 'admin/content/edit/'
        };

        this.edit = function(id) {
            return $http.get(urls.edit + id);
        };

        this.save = function(content, translations) {

            var data = {
                content: content,
                translations: translations
            }

            return $http({
                method: 'POST',
                url: urls.update + content.id,
                data: $httpParamSerializer(data),
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

