angular.module('app')

    .controller('LayoutEditController', function ($scope, $rootScope, LayoutService, LayoutResource, $routeSegment, WindowFactory, $routeParams, layout, $mdSidenav, languages) {
        const vm = this;

        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        // Base url
        vm.section = `layout/${$routeParams.page_id}`;
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.page = layout.data.page;
        vm.pages = layout.data.pages;
        vm.roles = layout.data.roles;
        vm.widgets = layout.data.widgets;
        vm.languages = languages.data;

        vm.layoutService = LayoutService.init(vm.page.data.structure);

        WindowFactory.add($scope);

        vm.save = () => {
            LayoutResource.update({ id: vm.page.id }, vm.page, (response) => {
                $scope.$parent.pages = response.data.pages;
            });
        };

        vm.saveAndClose = () => {
            LayoutResource.update({ id: vm.page.id }, vm.page, (response) => {
                $scope.$parent.pages = response.data.pages;
                WindowFactory.back($scope);
            });
        };

        vm.closeRight = () => {
            $mdSidenav('right')
                .close();
        };

        vm.dropCallback = (item) => {
            return item.id ? item.id : item;
        };
    });
