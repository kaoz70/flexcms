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
    .controller('ConfigCtrl', function($scope, $rootScope, Config, $routeSegment, WindowFactory, Loading){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add();
        var panel = Loading.show();

        //Base url
        $scope.section = "config";

        $scope.config = {};
        $scope.pages = [];
        $scope.themes = [];

        Config.getGeneral().then(function (response) {
            $scope.config = response.data.data.config;
            $scope.pages = response.data.data.pages;
            $scope.themes = response.data.data.themes;
            Loading.hide(panel);
        });

        $scope.saveAndClose = function () {
            Config.save($scope.config);
            WindowFactory.remove($scope);
        };

    })

;