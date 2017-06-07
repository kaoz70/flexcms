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
    .controller('ImageConfigCreateController', function($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig){

        $scope.image = {
            image_section_id: $routeParams.section_id,
            force_jpg: true,
            optimize_original: true,
            quality: 80,
            watermark_alpha: 50
        };

        $scope.watermark_data = {};

        WindowFactory.add($scope);

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            var data = {
                image: $scope.image,
                file: $scope.watermark_data
            };

            if($scope.form.$valid) {
                ImageConfig.save(data, function (response) {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }

        };

    })

;