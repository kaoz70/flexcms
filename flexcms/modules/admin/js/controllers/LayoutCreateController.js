/**
 * Created by Miguel on 10-Apr-17.
 */
angular.module('app')

    .controller('LayoutCreateController', function ($scope, $rootScope, LayoutService, LayoutResource, $routeSegment, WindowFactory, layout, $mdSidenav) {
        const vm = this;
        let isNew = true;

        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        // Base url
        vm.section = 'layout/create';
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.page = layout.data.page;
        vm.page.data = {
            structure: [],
        };
        vm.page.enabled = true;
        vm.page.group_visibility = null;
        vm.pages = layout.data.pages;
        vm.roles = layout.data.roles;
        vm.widgets = layout.data.widgets;

        vm.layoutService = LayoutService.init(vm.page.data.structure);

        WindowFactory.add($scope);

        vm.save = () => {
            if(isNew) {
                LayoutResource.save(vm.page, (response) => {
                    $scope.$parent.pages = response.data.pages;
                    vm.page = response.data.page;
                    isNew = false;
                });
            } else {
                LayoutResource.update({ id: vm.page.id }, vm.page, (response) => {
                    $scope.$parent.pages = response.data.pages;
                });
            }
        };

        vm.saveAndClose = () => {
            if(isNew) {
                LayoutResource.save(vm.page, (response) => {
                    $scope.$parent.pages = response.data.pages;
                    WindowFactory.back($scope);
                });
            } else {
                LayoutResource.update(vm.page, (response) => {
                    $scope.$parent.pages = response.data.pages;
                    WindowFactory.back($scope);
                });
            }
        };

        vm.closeRight = () => {
            $mdSidenav('right')
                .close();
        };

        vm.dropCallback = (item) => {
            return item.id ? item.id : item;
        };
    });
