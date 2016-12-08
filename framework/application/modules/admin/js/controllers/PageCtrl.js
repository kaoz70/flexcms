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

            $rootScope.records = response.data.items;
            $scope.title = response.data.title;
            $scope.menu = response.data.menu;

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

;