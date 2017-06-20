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
    .controller('ConfigController', function($scope, $rootScope, Config, $routeSegment, WindowFactory, config){
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        // Base url
        vm.section = 'config';

        vm.config = config.data.config;
        vm.pages = config.data.pages;
        vm.themes = config.data.themes;
        vm.production = (vm.config.environment === 'production');

        vm.onProductionChange = (state) => {
            if (state) {
                vm.config.environment = 'production';
                vm.config.indent_html = false;
            } else {
                vm.config.environment = 'development';
            }
        };

        vm.saveAndClose = function () {
            Config.update(vm.config);
            WindowFactory.back($scope);
        };
    });
