(function() {
    'use strict';

    angular
        .module('app')
        .directive('toggle', easyPieChart);

    function easyPieChart() {
        return {
            restrict: 'A',
            link: function(scope, element, attributes) {
                if (attributes.toggle === 'easypiechart') {
                    var barColor = yima.getcolor(element.data('barcolor')) || yima.primary(),
                        trackColor = yima.getcolor(element.data('trackcolor')) || false,
                        scaleColor = yima.getcolor(element.data('scalecolor')) || false,
                        lineCap = element.data('linecap') || "round",
                        lineWidth = element.data('linewidth') || 3,
                        size = element.data('size') || 110,
                        animate = element.data('animate') || false;

                    element.easyPieChart({
                        barColor: barColor,
                        trackColor: trackColor,
                        scaleColor: scaleColor,
                        lineCap: lineCap,
                        lineWidth: lineWidth,
                        size: size,
                        animate: animate
                    });
                    element.find('.percent').css('line-height', size + 'px');
                }
            }
        }
    };
}());