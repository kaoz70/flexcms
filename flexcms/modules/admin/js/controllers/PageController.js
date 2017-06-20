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
    .controller('PageController', function ($scope, $rootScope, WindowFactory, $routeParams, Content, page, Selection) {
        const vm = this;

        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        // Base url
        vm.section = `page/${$routeParams.page_id}/content`;
        vm.title = '';
        vm.menu = [];
        vm.dragable = false;
        vm.selected = {};
        vm.query = '';

        vm.showDelete = true;
        vm.showReorder = true;

        vm.selection = new Selection();
        vm.selection.setDeleteCallback((node) => {
            Content.delete({ id: node.id }, (response) => {
                $scope.items = response.data;

                // Remove from the selected array
                vm.selection.remove(node.id);
            });
        });

        vm.treeOptions = {
            dropped() {
                Content.update({ id: $routeParams.page_id, method: 'reorder' }, $scope.items);
            },
        };

        WindowFactory.add($scope);

        $scope.items = page.data.items;
        vm.title = page.data.title;
        vm.menu = page.data.menu;
    });
