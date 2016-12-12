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
    .controller('LayoutCtrl', function($scope, $rootScope, Layout, $routeSegment, WindowFactory, $routeParams, Content, $window, Loading, $mdDialog, $mdColorPalette, $mdColors, $mdTheming, Selection){

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
        $scope.selected = [];

        WindowFactory.add();
        var panel = Loading.show();

        //Load the content
        Layout.get($routeParams.page_id, $scope).then(function (response) {

            $scope.languages = response.data.data.languages;
            $scope.page = response.data.data.page;
            $scope.pages = response.data.data.pages;
            $scope.roles = response.data.data.roles;
            $scope.rows = response.data.data.rows;

            Loading.hide(panel);

            $scope.$watch('rows', function(rows) {
                $scope.modelAsJson = angular.toJson(rows, true);
            }, true);

        });

        $scope.addRow = function (columns) {

            var cols = [];
            for(var i = 0; i < columns; i++) {
                cols.push({
                    class: '',
                    span: {
                        large: 12 / columns,
                        medium: 12 / columns,
                        small: 12 / columns,
                    },
                    offset: {
                        large: 0,
                        medium: 0,
                        small: 0,
                    },
                    push: {
                        large: 0,
                        medium: 0,
                        small: 0,
                    },
                    pull: {
                        large: 0,
                        medium: 0,
                        small: 0,
                    }
                });
            }

            $scope.rows.push({
                class: '',
                columns: cols,
                expanded: false
            })
        };
        
        $scope.calculateSpans = function (column, length) {

            var span;

            if(column.span) {
                span = column.span.large;
            } else {
                span = 100 / length;
            }

            return span;

        };

        $scope.delete = function (ev) {



        }

    })

;