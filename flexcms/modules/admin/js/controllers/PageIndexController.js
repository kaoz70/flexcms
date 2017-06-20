/**
 * Created by Miguel on 24-Apr-17.
 */

angular.module('app')
    .controller('PageIndexController', function ($rootScope) {
        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
    });
