/**
 * @ngdoc service
 * @name app:Language
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('LayoutService', function(/*$q, $http, $httpParamSerializer, Notification, Response,*/ $mdDialog, $mdSidenav){

        /*var urls = {
            update: 'admin/layout/update/',
            delete: 'admin/layout/delete/',
            insert: 'admin/layout/insert',
            edit: 'admin/layout/edit/'
        };

        this.get = function(id) {
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
        };*/

        this.addColumn = function(columns) {
            return {
                class: '',
                span: {
                    large: 12 / columns,
                    medium: 12 / columns,
                    small: 12 / columns
                },
                offset: {
                    large: 0,
                    medium: 0,
                    small: 0
                },
                push: {
                    large: 0,
                    medium: 0,
                    small: 0
                },
                pull: {
                    large: 0,
                    medium: 0,
                    small: 0
                }
            };
        };

        this.toggleRight = buildToggler('right');

        function buildToggler(componentId) {
            return function() {
                $mdSidenav(componentId).toggle();
            };
        }

        this.addRow = function(rows, columns) {

            var cols = [];
            for (var i = 0; i < columns; i++) {
                cols.push(this.addColumn(columns));
            }

            rows.push({
                class: '',
                columns: cols,
                expanded: false
            });
        };

        this.deleteRow = function(ev, index) {

            var confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta fila?')
                .textContent('Se eliminaran todas las columnas y widgets.')
                .ariaLabel('Eliminar fila')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                $scope.rows.splice(index, 1);
            });

        };

        this.deleteColumn = function(ev, index, columns) {

            var confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta columna?')
                .textContent('Se eliminaran todos los widgets.')
                .ariaLabel('Eliminar columna')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                columns.splice(index, 1);
            });

        };

        this.addColumn = function(ev, row) {

            var confirm = $mdDialog.confirm()
                .title('Añadir nueva columna')
                .textContent('Desea añadir una nueva columna?')
                .ariaLabel('Añadir columna')
                .targetEvent(ev)
                .ok('Añadir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                row.columns.push(this.addColumn(1));
            });

        };

});

