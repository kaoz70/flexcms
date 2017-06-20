/**
 * Created by Miguel on 10-Apr-17.
 */
angular.module('app')
    .controller('LanguageEditController', function ($scope, $rootScope, Language, $routeSegment, WindowFactory, $filter, language) {
        const vm = this;

        // We store the original data here in case the user closes the panel (cancel)
        const origData = angular.copy(language.data);

        // Find the content by id in the records array
        const ct = $filter('filter')($scope.$parent.items, {
            id: parseInt(language.data.id, 10),
        }, true);

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = false;

        WindowFactory.add($scope);

        vm.language = language.data;
        vm.title = language.data.name;

        // Change the name in the item list
        $scope.$watch('vm.language.name', (v) => {
            ct[0].name = v;
        });

        vm.save = () => {
            Language.update({ id: vm.language.id }, vm.language);
            WindowFactory.back($scope);
        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        vm.closeHandler = () => {
            // Reset the data
            angular.copy(origData, ct[0]);
        };
    });
