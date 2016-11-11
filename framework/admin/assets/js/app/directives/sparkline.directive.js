(function() {
    'use strict';

    angular
        .module('app')
        .directive('sparkline', sparkline);

    function sparkline() {
        return {
            restrict: 'A',
            link: function(scope, element, attributes) {
                if (attributes.sparkline === "bar") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'bar',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        barColor: yima.getcolor(element.data('barcolor')),
                        negBarColor: yima.getcolor(element.data('negbarcolor')),
                        zeroColor: yima.getcolor(element.data('zerocolor')),
                        barWidth: element.data('barwidth'),
                        barSpacing: element.data('barspacing'),
                        stackedBarColor: element.data('stackedbarcolor')
                    });
                } else if (attributes.sparkline === "line") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'line',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        fillColor: yima.getcolor(element.data('fillcolor')),
                        lineColor: yima.getcolor(element.data('linecolor')),
                        spotRadius: element.data('spotradius'),
                        lineWidth: element.data('linewidth'),
                        spotColor: yima.getcolor(element.data('spotcolor')),
                        minSpotColor: yima.getcolor(element.data('minspotcolor')),
                        maxSpotColor: yima.getcolor(element.data('maxspotcolor')),
                        highlightSpotColor: yima.getcolor(element.data('highlightspotcolor')),
                        highlightLineColor: yima.getcolor(element.data('highlightlinecolor'))
                    });
                } else if (attributes.sparkline === "compositeline") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'line',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        lineColor: yima.getcolor(element.data('linecolor')),
                        fillColor: yima.getcolor(element.data('fillcolor')),
                        spotRadius: element.data('spotradius'),
                        lineWidth: element.data('linewidth'),
                        spotColor: yima.getcolor(element.data('spotcolor')),
                        minSpotColor: yima.getcolor(element.data('minspotcolor')),
                        maxSpotColor: yima.getcolor(element.data('maxspotcolor')),
                        highlightSpotColor: yima.getcolor(element.data('highlightspotcolor')),
                        highlightLineColor: yima.getcolor(element.data('highlightlinecolor'))
                    });
                    element.sparkline(yima.stringToIntArray(element.data('compositeset')), {
                        type: 'line',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        lineColor: yima.getcolor(element.data('secondlinecolor')),
                        fillColor: yima.getcolor(element.data('secondfillcolor')),
                        lineWidth: element.data('secondlinewidth'),
                        spotRadius: element.data('spotradius'),
                        spotColor: yima.getcolor(element.data('spotcolor')),
                        minSpotColor: yima.getcolor(element.data('minspotcolor')),
                        maxSpotColor: yima.getcolor(element.data('maxspotcolor')),
                        highlightSpotColor: yima.getcolor(element.data('highlightspotcolor')),
                        highlightLineColor: yima.getcolor(element.data('highlightlinecolor')),
                        composite: true
                    });
                } else if (attributes.sparkline === "compositebar") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'bar',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        barColor: yima.getcolor(element.data('barcolor')),
                        negBarColor: yima.getcolor(element.data('negbarcolor')),
                        zeroColor: yima.getcolor(element.data('zerocolor')),
                        barWidth: element.data('barwidth'),
                        barSpacing: element.data('barspacing'),
                        stackedBarColor: yima.getcolor(element.data('stackedbarcolor'))
                    });
                    element.sparkline(yima.stringToIntArray(element.data('compositeset')), {
                        type: 'line',
                        height: element.data('height'),
                        disableHiddenCheck: true,
                        width: element.data('width'),
                        lineColor: yima.getcolor(element.data('linecolor')),
                        fillColor: yima.getcolor(element.data('fillcolor')),
                        spotRadius: element.data('spotradius'),
                        lineWidth: element.data('linewidth'),
                        spotColor: yima.getcolor(element.data('spotcolor')),
                        minSpotColor: yima.getcolor(element.data('minspotcolor')),
                        maxSpotColor: yima.getcolor(element.data('maxspotcolor')),
                        highlightSpotColor: yima.getcolor(element.data('highlightspotcolor')),
                        highlightLineColor: yima.getcolor(element.data('highlightlinecolor')),
                        composite: true
                    });

                } else if (attributes.sparkline === "tristate") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'tristate',
                        disableHiddenCheck: true,
                        height: element.data('height'),
                        width: element.data('width'),
                        posBarColor: yima.getcolor(element.data('posbarcolor')),
                        negBarColor: yima.getcolor(element.data('negbarcolor')),
                        zeroBarColor: yima.getcolor(element.data('zerobarcolor')),
                        barWidth: element.data('barwidth'),
                        barSpacing: element.data('barspacing'),
                        zeroAxis: element.data('zeroaxis')
                    });
                } else if (attributes.sparkline === "discrete") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'discrete',
                        disableHiddenCheck: true,
                        lineHeight: element.data('lineheight'),
                        lineColor: yima.getcolor(element.data('linecolor')),
                        thresholdValue: element.data('thresholdvalue'),
                        thresholdColor: element.data('thresholdcolor')
                    });
                } else if (attributes.sparkline === "bullet") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'bullet',
                        disableHiddenCheck: true,
                        targetcolor: element.data('targetcolor'),
                        performanceColor: element.data('performancecolor'),
                        rangeColors: element.data('rangecolors')
                    });
                } else if (attributes.sparkline === "box") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'box',
                        disableHiddenCheck: true,
                    });
                } else if (attributes.sparkline === "pie") {
                    element.sparkline(yima.stringToIntArray(element.data('set')), {
                        type: 'pie',
                        disableHiddenCheck: true,
                        width: element.data('width'),
                        height: element.data('height'),
                        sliceColors: element.data('slicecolors'),
                        borderColor: yima.getcolor(element.data('bordercolor'))
                    });
                }
            }
        }
    };
}());