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
    .controller('PageCtrl', function($scope, $rootScope, Page, $routeSegment, WindowFactory, $routeParams, Content, $window, Loading, $mdDialog, BASE_PATH, $mdColorPalette, $mdColors, $mdTheming, Selection){

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

            var items = $scope.deleteSelection,
                parentScope = $scope;

            function DialogController($scope, $mdDialog) {

                if(items.length > 1) {
                    $scope.message = '¿Est&aacute; seguro de que desea eliminar estos ' + items.length + ' elementos?';
                } else {
                    $scope.message = '¿Est&aacute; seguro de que desea eliminar este elemento?';
                }

                $scope.cancel = function() {
                    $mdDialog.hide();
                };

                $scope.delete = function() {

                    Content.delete(items, $routeParams.page_id).then(function (response) {

                        $rootScope.records = response.data.data;

                        $mdDialog.hide();
                        parentScope.deleteSelection = [];

                    });

                };

            }

            $mdDialog.show({
                templateUrl: BASE_PATH + 'admin/WarningDialog',
                parent: angular.element(document.body),
                targetEvent: ev,
                controller: DialogController,
                clickOutsideToClose:true
            });

        }

    })

;