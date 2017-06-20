/**
 * Created by Miguel on 19-Jan-17.
 */

/**
 * @ngdoc controller
 * @name App:ImageConfigCtrl
 *
 * @description
 *
 *
 * @requires $scope
 * */
angular.module('app')
    .controller('ImageConfigEditController', function ($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig, image) {
        const vm = this;

        vm.image = image.data.image;
        vm.watermark_data = image.data.watermark;

        console.log(vm.watermark_data);

        WindowFactory.add($scope);

        vm.saveAndClose = () => {
            const data = {
                image: vm.image,
                file: vm.watermark_data,
            };

            // Check for a valid form
            vm.form.$setSubmitted();

            if (vm.form.$valid) {
                ImageConfig.update({
                    image_id: vm.image.id,
                }, data, (response) => {
                    vm.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }
        };
    })

;
