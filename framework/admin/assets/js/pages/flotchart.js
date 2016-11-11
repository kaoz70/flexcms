var yimaPage = function() {
    var initilize = function() {
        //Real-Time Chart//
        var firstRealTimeData = [],
            secondRealTimeData = [],
            totalPoints = 300;

        var getSeriesObj = function() {
            return [
                {
                    data: getFirstDataSet(),
                    lines: {
                        show: true,
                        lineWidth: 1,
                        fill: true,
                        fillColor: {
                            colors: [
                                {
                                    opacity: 0
                                }, {
                                    opacity: 1
                                }
                            ]
                        },
                        steps: false
                    },
                    shadowSize: 0
                }, {
                    data: getSecondDataSet(),
                    lines: {
                        lineWidth: 0,
                        fill: true,
                        fillColor: {
                            colors: [
                                {
                                    opacity: .5
                                }, {
                                    opacity: 1
                                }
                            ]
                        },
                        steps: false
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

                var prev = firstRealTimeData.length > 0 ? firstRealTimeData[firstRealTimeData.length - 1] : 50,
                    y = prev + Math.random() * 10 - 5;

                if (y < 0) {
                    y = 0;
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

                var prev = secondRealTimeData.length > 0 ? firstRealTimeData[secondRealTimeData.length] : 50,
                    y = prev - 25;

                if (y < 0) {
                    y = 0;
                } else if (y > 100) {
                    y = 100;
                }
                secondRealTimeData.push(y);
            }


            var res = [];
            for (var i = 0; i < secondRealTimeData.length; ++i) {
                res.push([i, secondRealTimeData[i]]);
            }

            return res;
        }

        // Set up the control widget
        var updateInterval = 500;
        var plot = $.plot("#realtime-chart", getSeriesObj(), {
            yaxis: {
                color: '#f3f3f3',
                min: 0,
                max: 100,
                tickFormatter: function(val, axis) {
                    return "";
                }
            },
            xaxis: {
                color: '#f3f3f3',
                min: 0,
                max: 100,
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
            colors: ['#eee', '#29c7ca'],
        });

        function update() {

            plot.setData(getSeriesObj());

            plot.draw();
            setTimeout(update, updateInterval);
        }

        update();

        //Bar Chart
        var d1_1 = [];
        for (var i = 1; i <= 10; i += 1)
            d1_1.push([i, parseInt(Math.random() * 50)]);

        var d1_2 = [];
        for (var i = 1; i <= 10; i += 1)
            d1_2.push([i, parseInt(Math.random() * 50)]);

        var d1_3 = [];
        for (var i = 1; i <= 10; i += 1)
            d1_3.push([i, parseInt(Math.random() * 50)]);

        var data1 = [
            {
                label: "Windows Phone",
                data: d1_1,
                bars: {
                    show: true,
                    order: 1,
                    fillColor: { colors: [{ color: '#ccc' }, { color: '#ccc' }] }
                },
                color: '#ccc'
            },
            {
                label: "Android",
                data: d1_2,
                bars: {
                    show: true,
                    order: 2,
                    fillColor: { colors: [{ color: '#27292c' }, { color: '#27292c' }] }
                },
                color: '#27292c'
            },
            {
                label: "IOS",
                data: d1_3,
                bars: {
                    show: true,
                    order: 3,
                    fillColor: { colors: [{ color: '#29c7ca' }, { color: '#29c7ca' }] }
                },
                color: '#29c7ca'
            }
        ];

        $.plot($("#bar-chart"), data1, {
            bars: {
                barWidth: 0.2,
                lineWidth: 0.5,
                borderWidth: 0,
                fillColor: { colors: [{ opacity: 0.4 }, { opacity: 1 }] }
            },
            xaxis: {
                color: '#eee'
            },
            yaxis: {
                color: '#eee'
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                aboveData: false
            },
            legend: true,
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false,
                content: "<b>%s</b> : <span>%x</span> : <span>%y</span>",
            }
        });

        //Multi Type Chart

        var multiChartData = [
            {
                color: '#666',
                label: "Direct Visits",
                data: [
                    [3, 2], [4, 5], [5, 4], [6, 11], [7, 12], [8, 11], [9, 8], [10, 14], [11, 12], [12, 16], [13, 9],
                    [14, 10], [15, 14], [16, 15], [17, 9]
                ],

                lines: {
                    show: true,
                    fill: true,
                    lineWidth: .1,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0
                            }, {
                                opacity: 0.4
                            }
                        ]
                    }
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
            {
                color: '#29c7ca',
                label: "Referral Visits",
                data: [
                    [3, 10], [4, 13], [5, 12], [6, 16], [7, 19], [8, 19], [9, 24], [10, 19], [11, 18], [12, 21], [13, 17],
                    [14, 14], [15, 12], [16, 14], [17, 15]
                ],
                bars: {
                    order: 1,
                    show: true,
                    borderWidth: 0,
                    barWidth: 0.4,
                    lineWidth: .5,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0.4
                            }, {
                                opacity: 1
                            }
                        ]
                    }
                }
            },
            {
                color: '#FFC107',
                label: "Search Engines",
                data: [
                    [3, 14], [4, 11], [5, 10], [6, 9], [7, 5], [8, 8], [9, 5], [10, 6], [11, 4], [12, 7], [13, 4],
                    [14, 3], [15, 4], [16, 6], [17, 4]
                ],
                lines: {
                    show: true,
                    fill: false,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0.3
                            }, {
                                opacity: 0
                            }
                        ]
                    }
                },
                points: {
                    show: true
                }
            }
        ];
        var multiChartOptions = {
            legend: {
                show: false
            },
            xaxis: {
                tickDecimals: 0,
                color: '#f3f3f3'
            },
            yaxis: {
                min: 0,
                color: '#f3f3f3',
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                aboveData: false,
                color: '#fbfbfb'

            },
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false,
                content: " <b>%x May</b> , <b>%s</b> : <span>%y</span>",
            }
        };
        $.plot($("#multi-chart"), multiChartData, multiChartOptions);


        //Selecatable Line Chart
        var data = [
            {
                color: '#29c7ca',
                label: "Bootstrap",
                data: [[1990, 18.9], [1991, 18.7], [1992, 18.4], [1993, 19.3], [1994, 19.5], [1995, 19.3], [1996, 19.4], [1997, 20.2], [1998, 19.8], [1999, 19.9], [2000, 20.4], [2001, 20.1], [2002, 20.0], [2003, 19.8], [2004, 20.4]]
            }, {
                color: '#282828',
                label: "Foundation",
                data: [[1990, 10.0], [1991, 11.3], [1992, 9.9], [1993, 9.6], [1994, 9.5], [1995, 9.5], [1996, 9.9], [1997, 9.3], [1998, 9.2], [1999, 9.2], [2000, 9.5], [2001, 9.6], [2002, 9.3], [2003, 9.4], [2004, 9.79]]
            }, {
                color: '#ccc',
                label: "Semantic",
                data: [[1990, 5.8], [1991, 6.0], [1992, 5.9], [1993, 5.5], [1994, 5.7], [1995, 5.3], [1996, 6.1], [1997, 5.4], [1998, 5.4], [1999, 5.1], [2000, 5.2], [2001, 5.4], [2002, 6.2], [2003, 5.9], [2004, 5.89]]
            }, {
                color: '#FFC107',
                label: "Gumbi",
                data: [[1990, 8.3], [1991, 8.3], [1992, 7.8], [1993, 8.3], [1994, 8.4], [1995, 5.9], [1996, 6.4], [1997, 6.7], [1998, 6.9], [1999, 7.6], [2000, 7.4], [2001, 8.1], [2002, 12.5], [2003, 9.9], [2004, 19.0]]
            }
        ];

        var options = {
            series: {
                lines: {
                    show: true
                },
                points: {
                    show: true
                }
            },
            legend: {
                noColumns: 1
            },
            xaxis: {
                tickDecimals: 0,
                color: '#eee'
            },
            yaxis: {
                min: 0,
                color: '#eee'
            },
            selection: {
                mode: "x"
            },
            grid: {
                hoverable: true,
                clickable: false,
                borderWidth: 0,
                aboveData: false
            },
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false,
                content: "<b>%s</b> : <span>%x</span> : <span>%y</span>",
            },
            crosshair: {
                mode: "x"
            }
        };

        var lineChartPlaceholder = $("#line-chart");

        lineChartPlaceholder.bind("plotselected", function(event, ranges) {

            var zoom = $("#zoom").is(":checked");

            if (zoom) {
                plot = $.plot(lineChartPlaceholder, data, $.extend(true, {}, options, {
                    xaxis: {
                        min: ranges.xaxis.from,
                        max: ranges.xaxis.to
                    }
                }));
            }
        });

        lineChartPlaceholder.bind("plotunselected", function(event) {
            // Do Some Work
        });

        $.plot(lineChartPlaceholder, data, options);

        $("#clearSelection").click(function() {
            plot.clearSelection();
        });

        $("#setSelection").click(function() {
            plot.setSelection({
                xaxis: {
                    from: 1994,
                    to: 1995
                }
            });
        });

        //Stacked Line Chart
        var d1 = [];
        for (var i = 0; i <= 10; i += 1)
            d1.push([i, parseInt(Math.random() * 30)]);

        var d2 = [];
        for (var i = 0; i <= 10; i += 1)
            d2.push([i, parseInt(Math.random() * 30)]);

        var d3 = [];
        for (var i = 0; i <= 10; i += 1)
            d3.push([i, parseInt(Math.random() * 30)]);

        var data1 = [
            {
                label: "Windows Phone",
                data: d1,
                color: '#27292c'
            },
            {
                label: "Android",
                data: d2,
                color: '#FFC107'
            },
            {
                label: "IOS",
                data: d3,
                color: '#1dbc9c'
            }
        ];

        var stack = 0,
            bars = false,
            lines = true,
            steps = false;

        function plotWithOptions() {
            $.plot($("#stacked-chart"), data1, {
                series: {
                    stack: stack,
                    lines: {
                        lineWidth: 0,
                        show: lines,
                        fill: true,
                        steps: steps
                    },
                    bars: {
                        show: bars,
                        barWidth: 0.4
                    }
                },
                xaxis: {
                    color: '#eee'
                },
                yaxis: {
                    color: '#eee'
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false
                },
                legend: {
                    noColumns: 3
                },
            });
        }

        $("#stackcontroll input").click(function(e) {
            e.preventDefault();
            stack = $(this).val() == "With stacking" ? true : null;
            plotWithOptions();
        });
        $("#typecontroll input").click(function(e) {
            e.preventDefault();
            bars = $(this).val().indexOf("Bars") != -1;
            lines = $(this).val().indexOf("Lines") != -1;
            steps = $(this).val().indexOf("steps") != -1;
            plotWithOptions();
        });

        plotWithOptions();

        //Pie Chart
        var data = [
            { label: "Windows", data: [[1, 10]], color: '#55606e' },
            { label: "Linux", data: [[1, 30]], color: '#29c7ca' },
            { label: "Mac OS X", data: [[1, 90]], color: '#27292c' },
            { label: "Android", data: [[1, 70]], color: '#cecece' },
            { label: "Unix", data: [[1, 80]], color: '#FFC107' }
        ];


        var placeholder = $("#pie-chart");

        $("#example-1").click(function() {

            placeholder.unbind();

            $("#title").text("Default pie chart");
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        gradient: {
                            radial: true,
                            colors: [
                                { opacity: 0.5 },
                                { opacity: 1.0 }
                            ]
                        }
                    }
                }
            });
        });

        $("#example-2").click(function() {

            placeholder.unbind();

            $("#title").text("Default without legend");
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-3").click(function() {

            placeholder.unbind();

            $("#title").text("Custom Label Formatter");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: labelFormatter,
                            background: {
                                opacity: 0.8
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-4").click(function() {

            placeholder.unbind();

            $("#title").text("Label Radius");
            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: labelFormatter,
                            background: {
                                opacity: 0.5
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-5").click(function() {

            placeholder.unbind();

            $("#title").text("Label Styles #1");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: labelFormatter,
                            background: {
                                opacity: 0.5,
                                color: "#000"
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-6").click(function() {

            placeholder.unbind();

            $("#title").text("Label Styles #2");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 3 / 4,
                        label: {
                            show: true,
                            radius: 3 / 4,
                            formatter: labelFormatter,
                            background: {
                                opacity: 0.5,
                                color: "#000"
                            }
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-7").click(function() {

            placeholder.unbind();

            $("#title").text("Hidden Labels");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        label: {
                            show: true,
                            radius: 2 / 3,
                            formatter: labelFormatter,
                            threshold: 0.1
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-8").click(function() {

            placeholder.unbind();

            $("#title").text("Combined Slice");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        combine: {
                            color: "#999",
                            threshold: 0.05
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-9").click(function() {

            placeholder.unbind();

            $("#title").text("Rectangular Pie");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 500,
                        label: {
                            show: true,
                            formatter: labelFormatter,
                            threshold: 0.1
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-10").click(function() {

            placeholder.unbind();

            $("#title").text("Tilted Pie");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true,
                        radius: 1,
                        tilt: 0.5,
                        label: {
                            show: true,
                            radius: 1,
                            formatter: labelFormatter,
                            background: {
                                opacity: 0.8
                            }
                        },
                        combine: {
                            color: "#999",
                            threshold: 0.1
                        }
                    }
                },
                legend: {
                    show: false
                }
            });
        });

        $("#example-11").click(function() {

            placeholder.unbind();

            $("#title").text("Donut Hole");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        innerRadius: 0.5,
                        show: true,
                        gradient: {
                            radial: true,
                            colors: [
                                { opacity: 1.0 },
                                { opacity: 1.0 },
                                { opacity: 1.0 },
                                { opacity: 0.5 },
                                { opacity: 1.0 }
                            ]
                        }
                    }
                }
            });
        });

        $("#example-12").click(function() {

            placeholder.unbind();

            $("#title").text("Interactivity");

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        show: true
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: true
                }
            });

            placeholder.bind("plothover", function(event, pos, obj) {

                if (!obj) {
                    return;
                }

                var percent = parseFloat(obj.series.percent).toFixed(2);
                $("#hover").html("<span style='font-weight:bold; color:" + obj.series.color + "'>" + obj.series.label + " (" + percent + "%)</span>");
            });

            placeholder.bind("plotclick", function(event, pos, obj) {

                if (!obj) {
                    return;
                }

                percent = parseFloat(obj.series.percent).toFixed(2);
                alert("" + obj.series.label + ": " + percent + "%");
            });
        });

        // Show the initial default chart

        $("#example-1").click();


        // A custom label formatter used by several of the plots

        function labelFormatter(label, series) {
            return "<div style='font-size:8pt; text-align:center; padding:2px; color:white;'>" + label + "<br/>" + Math.round(series.percent) + "%</div>";
        }
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