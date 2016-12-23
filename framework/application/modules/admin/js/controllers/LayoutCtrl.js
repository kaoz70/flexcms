/**
 * @ngdoc controller
 * @name App:LanguageCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('LayoutCtrl', function($scope, $rootScope, Layout, $routeSegment, $mdSidenav, WindowFactory, $routeParams, Content, $window, Loading, $mdDialog, $mdColorPalette, $mdColors, $mdTheming, Selection){

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        //Base url
        $scope.section = "layout/" + $routeParams.page_id;
        $scope.query = "";
        $scope.languages = [];
        $scope.page = {};
        $scope.pages = [];
        $scope.roles = [];
        $scope.rows = [];
        $scope.widgets = [];
        $scope.selected = [];
        $scope.device = "large";

        WindowFactory.add();
        var panel = Loading.show();

        //Load the content
        Layout.get($routeParams.page_id, $scope).then(function (response) {

            $scope.languages = response.data.data.page.translations;
            $scope.page = response.data.data.page.content;
            $scope.pages = response.data.data.pages;
            $scope.roles = response.data.data.roles;
            $scope.rows = response.data.data.rows;
            $scope.widgets = response.data.data.widgets;

            Loading.hide(panel);

            $scope.$watch('rows', function(rows) {
                $scope.modelAsJson = angular.toJson(rows, true);
            }, true);

        });

        $scope.toggleRight = buildToggler('right');

        function buildToggler(componentId) {
            return function() {
                $mdSidenav(componentId).toggle();
            }
        }

        $scope.addRow = function (columns) {

            var cols = [];
            for(var i = 0; i < columns; i++) {
                cols.push(addColumn(columns));
            }

            $scope.rows.push({
                class: '',
                columns: cols,
                expanded: false
            })
        };

        var addColumn = function (columns) {
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
            }
        };

        $scope.deleteRow = function (ev, index) {

            var confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta fila?')
                .textContent('Se eliminaran todas las columnas y widgets.')
                .ariaLabel('Eliminar fila')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                $scope.rows.splice(index, 1)
            });

        }

        $scope.deleteColumn = function (ev, index, columns) {

            var confirm = $mdDialog.confirm()
                .title('Quiere eliminar esta columna?')
                .textContent('Se eliminaran todos los widgets.')
                .ariaLabel('Eliminar columna')
                .targetEvent(ev)
                .ok('Eliminar')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                columns.splice(index, 1)
            });

        }

        $scope.addColumn = function (ev, row) {

            var confirm = $mdDialog.confirm()
                .title('Añadir nueva columna')
                .textContent('Desea añadir una nueva columna?')
                .ariaLabel('Añadir columna')
                .targetEvent(ev)
                .ok('Añadir')
                .cancel('Cancelar');

            $mdDialog.show(confirm).then(function() {
                row.columns.push(addColumn(1));
            });

        }

    })

;