/**
 * @ngdoc directive
 * @name App:CollapseDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('collapse', function () {
        return {
            restrict: 'A',

            link: function ($scope, ngElement, attributes) {
                var element = ngElement[0];

                $scope.$watch(attributes.collapse, function (collapse) {
                    var newHeight = collapse ? 0 : getElementAutoHeight();
                    element.style.height = newHeight + 'px';
                    ngElement.toggleClass('collapsed', collapse);
                });

                function getElementAutoHeight() {
                    var currentHeight = getElementCurrentHeight();

                    element.style.height = 'auto';
                    var autoHeight = getElementCurrentHeight();

                    element.style.height = currentHeight;
                    getElementCurrentHeight(); // Force the browser to recalc height after moving it back to normal

                    return autoHeight;
                }

                function getElementCurrentHeight() {
                    return element.offsetHeight
                }
            }
        };
});
