var yimaPage = function() {
    var initilize = function() {
        var d1 = [[1990, 22.9], [1991, 21.7], [1992, 20.4], [1993, 19.3], [1994, 18.5], [1995, 18.3], [1996, 17.4], [1997, 17.2], [1998, 16.8], [1999, 16.9], [2000, 16.4], [2001, 15.1], [2002, 15.0], [2003, 13.8], [2004, 12.4]];

        var data1 = [
            {
                data: d1,
                color: yima.danger()
            }
        ];

        $.plot($("#line-chart-1"), data1, {
            series: {
                lines: {
                    lineWidth: 1,
                    show: true,
                    fill: true,
                    steps: false,
                },
                shadowSize: 0
            },
            grid: {
                show: false
            }
        });

        var d2 = [[1990, 12.9], [1991, 13.8], [1992, 15.0], [1993, 15.1], [1994, 16.4], [1995, 16.9], [1996, 16.8], [1997, 17.2], [1998, 17.4], [1999, 18.3], [2000, 18.5], [2001, 19.3], [2002, 20.4], [2003, 21.7], [2004, 22.4]];
        var data2 = [
            {
                data: d2,
                color: yima.success()
            }
        ];

        $.plot($("#line-chart-2"), data2, {
            series: {
                lines: {
                    lineWidth: 1,
                    show: true,
                    fill: true,
                    steps: false,
                },
                shadowSize: 0
            },
            grid: {
                show: false
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