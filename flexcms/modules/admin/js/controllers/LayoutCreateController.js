/**
 * Created by Miguel on 10-Apr-17.
 */
angular.module('app')

    .controller('LayoutCreateController', ($rootScope, LayoutService, $routeSegment, WindowFactory, layout) => {
        const vm = this;

        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
        console.log(3);

        // Base url
        vm.section = 'layout/create';
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.languages = layout.page.translations;
        vm.page = {};
        vm.pages = layout.pages;
        vm.roles = layout.roles;
        vm.rows = layout.rows;
        vm.widgets = layout.widgets;

        vm.layoutService = LayoutService;

        vm.$watch('rows', (rows) => {
            vm.modelAsJson = angular.toJson(rows, true);
        }, true);

        WindowFactory.add(vm);
    });
