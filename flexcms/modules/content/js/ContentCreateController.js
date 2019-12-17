/**
 * @ngdoc controller
 * @name App:LanguageCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')

    .controller('ContentCreateController', function ($scope, $rootScope, Content, $routeSegment, WindowFactory, $routeParams, languages, images) {
        const vm = this;

        // Close the sidebar on this controller
        $rootScope.isSidebarOpen = true;

        let isNew = true;
        const $parentScope = $scope.$parent;

        WindowFactory.add($scope);

        vm.content = {
            enabled: true,
            important: false,
            category_id: $routeParams.page_id,
            translations: languages.data,
            timezone: null,
            images: images.data,
        };

        vm.editorInit = false;

        vm.tinymceOptions = {
            plugins: 'link image code',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | code',
        };

        vm.save = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                if (isNew) {
                    Content.save(vm.content, (response) => {
                        // Set the new ID
                        vm.content.id = response.data.content.id;
                        $parentScope.items = response.data.items;
                        isNew = false;
                    });
                } else {
                    Content.update({ id: vm.content.id }, vm.content);
                }
            }
        };

        vm.saveAndClose = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                if (isNew) {
                    Content.save(vm.content, (response) => {
                        // Set the new ID
                        vm.content.id = response.data.content.id;
                        $parentScope.items = response.data.items;

                        WindowFactory.back($scope);
                    });
                } else {
                    Content.update({ id: vm.content.id }, vm.content, () => {
                        WindowFactory.back($scope);
                    });
                }
            }
        };
    });
