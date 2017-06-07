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
    .controller('ImageConfigEditController', function($scope, $rootScope, Config, $routeParams, WindowFactory, ImageConfig, image){

        $scope.image = image.data.image;
        $scope.watermark_data = image.data.watermark;

        WindowFactory.add($scope);

        $scope.saveAndClose = function () {

            //Check for a valid form
            $scope.form.$setSubmitted();

            var data = {
                image: $scope.image,
                file: $scope.watermark_data
            };

            if($scope.form.$valid) {
                ImageConfig.update({
                    image_id: $scope.image.id
                },data, function (response) {
                    $scope.$parent.items = response.data;
                    WindowFactory.back($scope);
                });
            }

        };

    })

;