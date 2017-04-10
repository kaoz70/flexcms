angular.module('app')

    .controller('LayoutEditController', ($rootScope, LayoutService, $routeSegment, WindowFactory, $routeParams, layout) => {
        const vm = this;

        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        // Base url
        vm.section = `layout/${$routeParams.page_id}`;
        vm.query = '';
        vm.selected = [];
        vm.device = 'large';

        vm.languages = layout.page.translations;
        vm.page = layout.page.content;
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
