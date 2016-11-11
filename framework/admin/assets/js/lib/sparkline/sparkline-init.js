$(document).ready(function () {
    var drawSparkline = function () {
        /*Bar*/
        var sparklinebars = $('[data-sparkline=bar]');
        $.each(sparklinebars, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'bar',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                barColor: yima.getcolor($(this).data('barcolor')),
                negBarColor: yima.getcolor($(this).data('negbarcolor')),
                zeroColor: yima.getcolor($(this).data('zerocolor')),
                barWidth: $(this).data('barwidth'),
                barSpacing: $(this).data('barspacing'),
                stackedBarColor: $(this).data('stackedbarcolor')
            });
        });

        /*Line*/
        var sparklinelines = $('[data-sparkline=line]');
        $.each(sparklinelines, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'line',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                fillColor: yima.getcolor($(this).data('fillcolor')),
                lineColor: yima.getcolor($(this).data('linecolor')),
                spotRadius: $(this).data('spotradius'),
                lineWidth: $(this).data('linewidth'),
                spotColor: yima.getcolor($(this).data('spotcolor')),
                minSpotColor: yima.getcolor($(this).data('minspotcolor')),
                maxSpotColor: yima.getcolor($(this).data('maxspotcolor')),
                highlightSpotColor: yima.getcolor($(this).data('highlightspotcolor')),
                highlightLineColor: yima.getcolor($(this).data('highlightlinecolor'))
            });
        });
        /*Composite Line*/
        var sparklinecompositelines = $('[data-sparkline=compositeline]');
        $.each(sparklinecompositelines, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'line',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                lineColor: yima.getcolor($(this).data('linecolor')),
                fillColor: yima.getcolor($(this).data('fillcolor')),
                spotRadius: $(this).data('spotradius'),
                lineWidth: $(this).data('linewidth'),
                spotColor: yima.getcolor($(this).data('spotcolor')),
                minSpotColor: yima.getcolor($(this).data('minspotcolor')),
                maxSpotColor: yima.getcolor($(this).data('maxspotcolor')),
                highlightSpotColor: yima.getcolor($(this).data('highlightspotcolor')),
                highlightLineColor: yima.getcolor($(this).data('highlightlinecolor'))
            });
            $(this).sparkline(yima.stringToIntArray($(this).data('compositeset')), {
                type: 'line',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                lineColor: yima.getcolor($(this).data('secondlinecolor')),
                fillColor: yima.getcolor($(this).data('secondfillcolor')),
                lineWidth: $(this).data('secondlinewidth'),
                spotRadius: $(this).data('spotradius'),
                spotColor: yima.getcolor($(this).data('spotcolor')),
                minSpotColor: yima.getcolor($(this).data('minspotcolor')),
                maxSpotColor: yima.getcolor($(this).data('maxspotcolor')),
                highlightSpotColor: yima.getcolor($(this).data('highlightspotcolor')),
                highlightLineColor: yima.getcolor($(this).data('highlightlinecolor')),
                composite: true
            });
        });

        /*Composite Bar*/
        var sparklinecompositebars = $('[data-sparkline=compositebar]');
        $.each(sparklinecompositebars, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'bar',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                barColor: yima.getcolor($(this).data('barcolor')),
                negBarColor: yima.getcolor($(this).data('negbarcolor')),
                zeroColor: yima.getcolor($(this).data('zerocolor')),
                barWidth: $(this).data('barwidth'),
                barSpacing: $(this).data('barspacing'),
                stackedBarColor: yima.getcolor($(this).data('stackedbarcolor'))
            });
            $(this).sparkline(yima.stringToIntArray($(this).data('compositeset')), {
                type: 'line',
                height: $(this).data('height'),
                disableHiddenCheck: true,
                width: $(this).data('width'),
                lineColor: yima.getcolor($(this).data('linecolor')),
                fillColor: yima.getcolor($(this).data('fillcolor')),
                spotRadius: $(this).data('spotradius'),
                lineWidth: $(this).data('linewidth'),
                spotColor: yima.getcolor($(this).data('spotcolor')),
                minSpotColor: yima.getcolor($(this).data('minspotcolor')),
                maxSpotColor: yima.getcolor($(this).data('maxspotcolor')),
                highlightSpotColor: yima.getcolor($(this).data('highlightspotcolor')),
                highlightLineColor: yima.getcolor($(this).data('highlightlinecolor')),
                composite: true
            });

        });

        /*Tristate*/
        var sparklinetristates = $('[data-sparkline=tristate]');
        $.each(sparklinetristates, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'tristate',
                disableHiddenCheck: true,
                height: $(this).data('height'),
                width: $(this).data('width'),
                posBarColor: yima.getcolor($(this).data('posbarcolor')),
                negBarColor: yima.getcolor($(this).data('negbarcolor')),
                zeroBarColor: yima.getcolor($(this).data('zerobarcolor')),
                barWidth: $(this).data('barwidth'),
                barSpacing: $(this).data('barspacing'),
                zeroAxis: $(this).data('zeroaxis')
            });
        });

        /*Descrete*/
        var sparklinediscretes = $('[data-sparkline=discrete]');
        $.each(sparklinediscretes, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'discrete',
                disableHiddenCheck: true,
                lineHeight: $(this).data('lineheight'),
                lineColor: yima.getcolor($(this).data('linecolor')),
                thresholdValue: $(this).data('thresholdvalue'),
                thresholdColor: $(this).data('thresholdcolor')
            });
        });

        /*Bullet*/
        var sparklinebullets = $('[data-sparkline=bullet]');
        $.each(sparklinebullets, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'bullet',
                disableHiddenCheck: true,
                targetcolor: $(this).data('targetcolor'),
                performanceColor: $(this).data('performancecolor'),
                rangeColors: $(this).data('rangecolors')
            });
        });

        /*Box Plot*/
        var sparklinebox = $('[data-sparkline=box]');
        $.each(sparklinebox, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'box',
                disableHiddenCheck: true,
            });
        });

        /*Pie*/
        var sparklinepie = $('[data-sparkline=pie]');
        $.each(sparklinepie, function () {
            $(this).sparkline(yima.stringToIntArray($(this).data('set')), {
                type: 'pie',
                disableHiddenCheck: true,
                width: $(this).data('width'),
                height: $(this).data('height'),
                sliceColors: $(this).data('slicecolors'),
                borderColor: yima.getcolor($(this).data('bordercolor'))
            });
        });

    };

    drawSparkline();

    var resizeChart;

    $(window).resize(function (e) {
        clearTimeout(resizeChart);
        resizeChart = setTimeout(function () {
            drawSparkline();
        }, 200);
    });
});
