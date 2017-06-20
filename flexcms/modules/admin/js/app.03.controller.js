(function () {
    angular
        .module('app')
        .controller('MainController', MainController);

    MainController.$inject = ['$scope', '$rootScope', 'Page', '$routeSegment', 'CMS', 'LayoutResource', '$mdDialog', 'BASE_PATH', '$document'];

    function MainController($scope, $rootScope, Page, $routeSegment, CMS, LayoutResource, $mdDialog, BASE_PATH, $document) {
        CMS.init();
        $rootScope.isSidebarOpen = true;

        $scope.$routeSegment = $routeSegment;
        $scope.pages = Page.get({ id: 'null' }, (response) => {
            $scope.pages = response.data;
        });

        $scope.openPanel = () => {
            $rootScope.isSidebarOpen = true;
        };

        $scope.closePanel = () => {
            $rootScope.isSidebarOpen = false;
        };

        const onDialogDelete = (id) => {
            LayoutResource.delete({ id }, (response) => {
                $scope.pages = response.data;
            });
        };

        $scope.deleteNode = (id) => {
            $mdDialog.show({
                templateUrl: `${BASE_PATH}admin/dialogs/DeleteDialog`,
                parent: angular.element($document[0].body),
                controller($scope) {
                    $scope.message = '¿Est&aacute; seguro de que quiere eliminar esta p&aacute;gina?';

                    $scope.cancel = () => {
                        $mdDialog.hide();
                    };

                    $scope.delete = () => {
                        onDialogDelete(id);
                        $mdDialog.hide();
                    };
                },
                clickOutsideToClose: true,
            });
        };

        $scope.treeOptions = {
            dropped() {
                console.log($scope.pages);

                Page.update({ id: 1 }, $scope.pages, (response) => {
                    console.log(response);
                });
            },
        };
    }
}());
