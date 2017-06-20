/**
 * Created by Miguel on 24-Apr-17.
 */
angular.module('app')
    .controller('PageIndexCtrl', function ($rootScope) {
        // Open the sidebar on this controller
        $rootScope.isSidebarOpen = true;
    })

    .controller('PageConfigController', function ($scope, $rootScope, ContentConfig, $routeSegment, WindowFactory, $routeParams, $mdConstant, config) {

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
