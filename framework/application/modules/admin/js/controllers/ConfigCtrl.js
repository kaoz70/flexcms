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
    .controller('ConfigCtrl', function($scope, $rootScope, Config, $routeSegment, WindowFactory, config){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        //Base url
        $scope.section = "config";

        $scope.config = config.data.config;
        $scope.pages = config.data.pages;
        $scope.themes = config.data.themes;
        $scope.production = ($scope.config.environment === 'production');

        $scope.onProductionChange = function(state) {
            if(state) {
                $scope.config.environment = 'production';
                $scope.config.indent_html = false;
            } else {
                $scope.config.environment = 'development';
            }
        };

        $scope.saveAndClose = function () {
            Config.update($scope.config);
            WindowFactory.back($scope);
        };

    })

;