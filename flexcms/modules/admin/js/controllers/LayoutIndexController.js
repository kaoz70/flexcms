angular.module('app')

    .controller('LayoutIndexController', function ($rootScope) {
        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
    });
