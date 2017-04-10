angular.module('app')

    .controller('LayoutIndexController', ($rootScope) => {
        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
    });
