var yimaPage = function() {
    var initilize = function() {
        //Real-Time Chart//
        var chartIsRunning = true;

        $('#chart-switch').change(function() {
            chartIsRunning = $(this).is(":checked");
        });

        var firstRealTimeData = [],
            secondRealTimeData = [],
            totalPoints = 100;

        var getSeriesObj = function() {
            return [
                {
                    data: getFirstDataSet(),
                    label: "Page Views",
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [
                                {
                                    opacity: 0
                                }, {
                                    opacity: .35
                                }
                            ]
                        },
                        steps: false,
                    },
                    shadowSize: 0
                }, {
                    data: getSecondDataSet(),
                    label: "Unique Users",
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [
                                {
                                    opacity: 0
                                }, {
                                    opacity: .35
                                }
                            ]
                        },
                        steps: false,
                    },
                    shadowSize: 0
                }
            ];
        };

        function getFirstDataSet() {
            if (firstRealTimeData.length > 0)
                firstRealTimeData = firstRealTimeData.slice(1);

            // Do a random walk

            while (firstRealTimeData.length < totalPoints) {

                var prev = firstRealTimeData.length > 0 ? firstRealTimeData[firstRealTimeData.length - 1] : 60,
                    y = prev + Math.random() * 10 - 5;

                if (y <= 10) {
                    y = 15;
                } else if (y > 100) {
                    y = 100;
                }
                firstRealTimeData.push(y);
            }

            // Zip the generated y values with the x values

            var res = [];
            for (var i = 0; i < firstRealTimeData.length; ++i) {
                res.push([i, firstRealTimeData[i]]);
            }

            return res;
        }

        function getSecondDataSet() {
            if (secondRealTimeData.length > 0)
                secondRealTimeData = secondRealTimeData.slice(1);

            // Do a random walk

            while (secondRealTimeData.length < totalPoints) {

                var prev = secondRealTimeData.length > 0 ? secondRealTimeData[secondRealTimeData.length - 1] : 60,
                    y = prev + Math.random() * 10 - 6;

                if (y <= 10) {
                    y = 15;
                } else if (y > 100) {
                    y = 100;
                }
                secondRealTimeData.push(y);
            }

            // Zip the generated y values with the x values

            var res = [];
            for (var i = 0; i < secondRealTimeData.length; ++i) {
                res.push([i, secondRealTimeData[i]]);
            }

            return res;
        }

        // Set up the control widget
        var updateInterval = 400;
        var plot = $.plot("#chart-realtime", getSeriesObj(), {
            yaxis: {
                max: 100,
                tickLength: 0,
                labelWidth: 45,
                font: {
                    size: 15,
                    weight: "200",
                    color: '#fff'
                }
            },
            xaxis: {
                tickLength: 0,
                tickFormatter: function(val, axis) {
                    return "";
                }
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                aboveData: false
            },
            colors: ['#fff', yima._default()],
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false,
                content: "<span>%s</span> : <b>%y.0</b>"
            },
            legend: {
                show: false
            }
        });

        function update() {

            plot.setData(getSeriesObj());
            if (chartIsRunning)
                plot.draw();
            setTimeout(update, updateInterval);
        }

        update();

        function rowUpdate() {

            //Faking Row Update
            var trNo = Math.floor((Math.random() * 10) + 1);
            var row = $('.table tr').eq(trNo);
            trNo = Math.floor((Math.random() * 10) + 1);
            row.insertAfter($('.table tr').eq(trNo));
            row.addClass("bg-highlight").delay(500).queue(function(next) {
                $(this).removeClass("bg-highlight");
                next();
            });

            setTimeout(rowUpdate, updateInterval * 3);
        }

        rowUpdate();

        $('.flot-y-axis > div').each(function() {
            var me = $(this);
            me.css('left', parseInt(me.css('left')) - 10 + 'px');
            me.css('top', parseInt(me.css('top')) + 5 + 'px');
        });
    }

    return {
        init: initilize
    }
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yimaPage.init();
    }
});