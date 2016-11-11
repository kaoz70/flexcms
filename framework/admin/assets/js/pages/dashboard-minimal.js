var yimaPage = function() {
    var initilize = function() {
        //Chart1
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
                label: "Visits",
                data: d1,
                color: yima._default()
            },
            {
                label: "Sells",
                data: d2,
                color: yima.warning()
            },
            {
                label: "Registers",
                data: d3,
                color: yima.primary()
            }
        ];


        $.plot($("#chart1"), data1, {
            series: {
                stack: true,
                lines: {
                    lineWidth: 0,
                    show: true,
                    fill: true,
                    fillColor: {
                        colors: [
                            {
                                opacity: 0
                            }, {
                                opacity: .5
                            }
                        ]
                    },
                    steps: false,
                },
                curvedLines: {
                    apply: true,
                    active: true,
                    monotonicFit: true
                }
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
                noColumns: 3,
                container: $("#chart1-legend")
            },
            tooltip: true,
            tooltipOpts: {
                defaultTheme: false,
                content: "<span>%s</span> : <b>%y.2</b>"
            }
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