/**
 * Created by Miguel on 24-Apr-17.
 */
angular.module('app')

    .controller('ContentEditController', function($scope, $rootScope, content, $routeSegment, WindowFactory, $routeParams, $filter, $mdConstant, Content){
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        // We store the original data here in case the user closes the panel (cancel)
        const origData = angular.copy(content.data);

        WindowFactory.add($scope);

        vm.content = content.data;

        // Keyword creation keys
        vm.keys = [$mdConstant.KEY_CODE.ENTER, $mdConstant.KEY_CODE.COMMA];

        // Find the content by id in the records array
        const ct = $filter('filter')($scope.$parent.items, {
            id: parseInt($routeParams.id, 10),
        }, true);

        const createDates = (cont) => {
            vm.content.publication_start = new Date(cont.publication_start);
            vm.content.publication_end = new Date(cont.publication_end);
        };

        createDates(vm.content);

        // Change the name in the item list
        $scope.$watch('vm.content.translations[0].translation.name', (v) => {
            ct[0].name = v;
        });

        vm.save = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                Content.update({ id: vm.content.id }, vm.content, (response) => {
                    vm.content = response.data.content;
                    createDates(vm.content);
                });
            }
        };

        vm.saveAndClose = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                Content.update({ id: vm.content.id }, vm.content);
                WindowFactory.back($scope);
            }
        };

        /**
         * Handler used when the user clicks on the close panel button
         */
        vm.closeHandler = () => {
            // Reset the data
            angular.copy(origData, ct[0]);
        };
    });
