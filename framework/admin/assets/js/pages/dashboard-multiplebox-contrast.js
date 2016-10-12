var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {

            //Chart1
            var d1 = [];
            for (var i = 0; i <= 12; i += 1)
                d1.push([i, parseInt(Math.random() * 50)]);

            var d2 = [];
            for (var i = 0; i <= 12; i += 1)
                d2.push([i, parseInt(Math.random() * 50)]);

            var data1 = [
                {
                    label: "Visits",
                    data: d1,
                    color: yima.info()
                },
                {
                    label: "Sells",
                    data: d2,
                    color: yima.danger()
                }
            ];


            $.plot($("#chart1"), data1, {
                series: {
                    stack: true,
                    lines: {
                        lineWidth: 1,
                        show: true,
                        fill: false,
                        fillColor: {
                            colors: [
                                {
                                    opacity: .3
                                }, {
                                    opacity: .3
                                }
                            ]
                        },
                        steps: false,
                    },
                    curvedLines: {
                        apply: true,
                        active: true,
                        monotonicFit: true
                    },
                    shadowSize: 0

                },
                yaxis: {
                    tickLength: 0,
                    tickFormatter: function(val, axis) {
                        return "";
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
                legend: {
                    noColumns: 2,
                    container: $("#chart1-legend")
                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false,
                    content: "<span>%s</span> : <b>%y.2</b>"
                }
            });

            //Chart2

            var chart2Data = [
                {
                    color: '#fff',
                    label: "Loss",
                    data: [
                        [3, 2], [4, 5], [5, 4], [6, 11], [7, 12], [8, 11], [9, 8], [10, 14], [11, 12], [12, 16], [13, 9],
                        [14, 10], [15, 14], [16, 15], [17, 9]
                    ],
                    lines: {
                        show: true,
                        fill: true,
                        fillColor: {
                            colors: [
                                {
                                    opacity: 0
                                }, {
                                    opacity: .2
                                }
                            ]
                        },
                        lineWidth: 0
                    }
                },
                {
                    color: yima.warning(),
                    label: "Profit",
                    data: [
                        [3, 14], [4, 11], [5, 10], [6, 9], [7, 5], [8, 8], [9, 5], [10, 6], [11, 4], [12, 7], [13, 4],
                        [14, 3], [15, 4], [16, 6], [17, 4]
                    ],
                    lines: {
                        show: true,
                        fill: false,
                        lineWidth: 1
                    },
                    shadowSize: 0
                }
            ];
            var chart2Options = {
                xaxis: {
                    tickDecimals: 0,
                    color: '#f3f3f3',
                    tickLength: 0,
                    tickFormatter: function(val, axis) {
                        return "";
                    }
                },
                yaxis: {
                    min: 0,
                    tickLength: 0,
                    color: '#f3f3f3',
                    tickFormatter: function(val, axis) {
                        return "";
                    }
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false,

                },
                legend: {
                    noColumns: 2,
                    container: $("#chart2-legend"),
                    color: "#fff"
                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false,
                    content: " <b>%x.0 May</b> , <b>%s</b> : <span>%y.0M</span>",
                }
            };
            $.plot($("#chart2"), chart2Data, chart2Options);


            //Initiate Vector Map
            $('#usa').vectorMap({
                map: 'usa_en',
                enableZoom: true,
                showTooltip: true,
                selectedRegion: 'TX',
                backgroundColor: yima._default(),
                borderColor: '#fff',
                borderOpacity: 0.25,
                borderWidth: 2,
                color: '#e5e5e5',
                hoverColor: yima.warning(),
                hoverOpacity: 0.5,
                selectedColor: yima.success(),
            });
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