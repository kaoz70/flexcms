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
    .controller('PageIndexCtrl', function ($rootScope) {
        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
    })

    .controller('PageCtrl', function ($scope, $rootScope, WindowFactory, $routeParams, Content, page, Selection) {
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
    })

    .controller('PageConfigCtrl', function ($scope, $rootScope, ContentConfig, $routeSegment, WindowFactory, $routeParams, $mdConstant, config) {

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        WindowFactory.add($scope);

        $scope.config = config.data.config;
        $scope.page = config.data.page;
        $scope.roles = config.data.roles;
        $scope.list_views = config.data.list_views;
        $scope.detail_views = config.data.detail_views;

        // Keyword creation keys
        $scope.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];

        /**
         * Executed after a successful save
         * @param response
         */
        const onSave = (response) => {
            $scope.$parent.items = response.data;
        };

        $scope.save = () => {
            ContentConfig.update({ id: $routeParams.widget_id }, config.data, (response) => {
                onSave(response);
            });
        };

        $scope.saveAndClose = () => {
            ContentConfig.update({ id: $routeParams.widget_id }, config.data, (response) => {
                onSave(response);
                WindowFactory.back($scope);
            });
        };
    });
