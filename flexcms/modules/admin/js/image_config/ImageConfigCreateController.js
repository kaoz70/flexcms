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
    .controller('ImageConfigCreateController', function ($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig) {
        const vm = this;

        vm.image = {
            image_section_id: $routeParams.section_id,
            force_jpg: true,
            optimize_original: true,
            quality: 80,
            watermark_alpha: 50,
        };

        vm.watermark_data = {};

        WindowFactory.add($scope);

        vm.saveAndClose = () => {
            // Check for a valid form
            vm.form.$setSubmitted();

            const data = {
                image: vm.image,
                file: vm.watermark_data,
            };

            if (vm.form.$valid) {
                ImageConfig.save(data, (response) => {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }
        };
    })

;
