var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            if (!Modernizr.mq('(max-width: 1600px)')) {
                $(".sidebar.form").load(yima.getAssetPath("Partials/Help.html"));
                yima.toggleFormSidebar('Help');
            }
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
                    color: yima.success()
                }
            ];


            $.plot($("#chart1"), data1, {
                series: {
                    stack: true,
                    lines: {
                        lineWidth: 0,
                        show: true,
                        fill: true,
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
            });

            //Chart2
            var d2 = [];
            for (var i = 0; i <= 5; i += 1)
                d2.push([i, parseInt(Math.random() * 10)]);

            var data1 = [
                {
                    label: "Payments",
                    data: d2,
                    color: '#fff'
                }
            ];


            $.plot($("#chart2"), data1, {
                series: {
                    stack: false,
                    lines: {
                        lineWidth: 0,
                        show: true,
                        fill: 1.0,
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
                    show: false
                },
            });

            //Chart3
            var d2 = [];
            for (var i = 0; i <= 5; i += 1)
                d2.push([i, parseInt(Math.random() * 10)]);

            var chart3data = [
                {
                    label: "Users",
                    data: d2,
                    color: '#fff'
                }
            ];

            //Sets The Hidden Chart Width
            $('#chart3').width($('#chart3').closest(".widget").width() + 16);


            $.plot($("#chart3"), chart3data, {
                series: {
                    stack: false,
                    lines: {
                        lineWidth: 0,
                        show: true,
                        fill: 1.0,
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
                    show: false
                },
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