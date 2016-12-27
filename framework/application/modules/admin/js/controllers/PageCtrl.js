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

    .controller('PageCtrl', function($scope, $rootScope, Page, $routeSegment, WindowFactory, $routeParams, Content, $window, Loading, $mdDialog, $mdColorPalette, $mdColors, $mdTheming, Selection){

        //Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        //Base url
        $scope.section = "page/" + $routeParams.page_id + "/content";
        $scope.title = "";
        $scope.menu = [];
        $scope.dragable = false;
        $scope.selected = {};
        $scope.query = "";
        $scope.deleteSelection = Selection.init($scope);
        $scope.toggleDeleteSelection = Selection.toggleSelection;
        $scope.close_url = "#/page/";

        $scope.showDelete = true;
        $scope.showReorder = true;

        $scope.treeOptions = {
            dropped: function (scope, modelData, sourceIndex) {
                Content.setOrder($rootScope.records, $routeParams.page_id);
            }
        };

        $scope.onItemClick = Selection.onItemClick;

        WindowFactory.add();
        var panel = Loading.show();

        //Load the content
        Page.getOne($routeParams.page_id, $scope).then(function (response) {

            $rootScope.records = response.data.data.items;
            $scope.title = response.data.data.title;
            $scope.menu = response.data.data.menu;

            Loading.hide(panel);

        });

        $scope.delete = function (ev) {

            Selection.delete(ev, function() {

                Content.delete($scope.deleteSelection, $routeParams.page_id).then(function (response) {

                    $rootScope.records = response.data.data;

                    $mdDialog.hide();
                    $scope.deleteSelection = [];

                });

            });

        }

    })

    .controller('PageConfigCtrl', function($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams, $mdConstant, Loading){

        //Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        WindowFactory.add();
        var panel = Loading.show();

        $scope.close_url = "#/page/" + $routeParams.page_id;
        $scope.languages = [];
        $scope.config = {};
        $scope.page = {};
        $scope.roles = [];
        $scope.list_views = [];
        $scope.detail_views = [];
        $scope.editorInit = false;

        //Keyword creation keys
        $scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];

        Content.getConfig($routeParams.widget_id).then(function (response) {

            $scope.config = response.data.data.config;
            $scope.page = response.data.data.content;
            $scope.roles = response.data.data.roles;
            $scope.languages = response.data.data.translations;
            $scope.list_views = response.data.data.list_views;
            $scope.detail_views = response.data.data.detail_views;

            Loading.hide(panel);
        });

        /**
         * Executed after a successful save
         * @param response
         */
        var onSave = function (response) {
            $rootScope.records = response.data.data;
        };

        $scope.save = function () {
            Content.setConfig($routeParams.widget_id, $scope).then(onSave);
        };

        $scope.saveAndClose = function () {
            Content.setConfig($routeParams.widget_id, $scope).then(onSave);
            WindowFactory.remove($scope);
        };

    })

;