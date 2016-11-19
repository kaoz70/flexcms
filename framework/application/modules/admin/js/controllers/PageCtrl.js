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
    .controller('PageCtrl', function($scope, $rootScope, Page, $routeSegment, WindowFactory, $routeParams, Content){

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        //Base url
        $scope.section = "page/" + $routeParams.page_id + "/content";
        $scope.title = "";
        $scope.menu = [];
        $scope.dragable = false;
        $scope.selected = {};

        $scope.onSortEnd = function () {
            Content.setOrder($rootScope.records, $routeParams.page_id);
        };

        WindowFactory.add();

        //Load the content
        Page.getOne($routeParams.page_id, $scope).then(function (response) {

            $rootScope.records = response.data.items;
            $scope.title = response.data.title;
            $scope.menu = response.data.menu;

        });

    })

;