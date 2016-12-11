/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Content', function($http, Request, Response){

        var urls = {
            update: 'admin/content/update/',
            delete: 'admin/content/delete/',
            edit: 'admin/content/edit/',
            config: 'admin/content/config/',
            config_save: 'admin/content/config_save/',
            reorder: 'admin/content/reorder/'
        };

        this.getOne = function(id) {
            return $http.get(urls.edit + id)
                .success(Response.validate)
                .error(Response.error);
        };

        this.delete = function(ids, page_id) {
            return Request.post(ids, urls.delete + page_id);
        };

        this.getConfig = function (widget_id) {
            return $http.get(urls.config + widget_id)
                .success(Response.validate)
                .error(Response.error);
        };

        this.setConfig = function (widget_id, $scope) {

            var data = {
                config: $scope.config,
                translations: JSON.stringify($scope.languages),
                page: $scope.page
            };

            return Request.post(data, urls.config_save + widget_id);

        };

        this.save = function($scope) {

            var data = {
                content: $scope.content,
                translations: JSON.stringify($scope.languages)
            };

            return Request.post(data, urls.update + $scope.content.id);

        };

        this.setOrder = function(list, page_id) {

            var order = [],
                data = {};

            angular.forEach(list, function(value) {
                order.push(value.id);
            });

            data.order = JSON.stringify(order);

            return Request.post(data, urls.reorder + page_id);

        };

});

