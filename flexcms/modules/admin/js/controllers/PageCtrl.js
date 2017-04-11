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
    .controller('PageIndexCtrl', function($rootScope){

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

    })

    .controller('PageCtrl', function($scope, $rootScope, Page, $routeSegment, WindowFactory, $routeParams, Content, page, $window, Loading, $mdDialog, $mdColorPalette, $mdColors, $mdTheming, Selection){

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        //Base url
        $scope.section = "page/" + $routeParams.page_id + "/content";
        $scope.title = "";
        $scope.menu = [];
        $scope.dragable = false;
        $scope.selected = {};
        $scope.query = "";

        $scope.showDelete = true;
        $scope.showReorder = true;

        $scope.selection = new Selection();
        $scope.selection.setDeleteCallback(function (node) {

            Content.delete({id: node.id}, function (response) {

                $scope.items = response.data;

                //Remove from the selected array
                $scope.selection.remove(node.id);

            });

        })

        $scope.treeOptions = {
            dropped: function () {
                Content.update({id: $routeParams.page_id, method: 'reorder'}, $scope.items);
            }
        };

        WindowFactory.add($scope);

        $scope.items = page.data.items;
        $scope.title = page.data.title;
        $scope.menu = page.data.menu;

    })

    .controller('PageConfigCtrl', function($scope, $rootScope, ContentConfig, $routeSegment, WindowFactory, $routeParams, $mdConstant, config){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        WindowFactory.add($scope);

        $scope.config = config.data.config;
        $scope.page = config.data.page;
        $scope.roles = config.data.roles;
        $scope.list_views = config.data.list_views;
        $scope.detail_views = config.data.detail_views;

        //Keyword creation keys
        $scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            $scope.$parent.items = response.data;
        };

        $scope.save = function () {
            ContentConfig.update({id: $routeParams.widget_id}, config.data, function (response) {
                onSave(response);
            });
        };

        $scope.saveAndClose = function () {

            ContentConfig.update({id: $routeParams.widget_id}, config.data, function (response) {
                onSave(response);
                WindowFactory.back($scope);
            });

        };

    })

;