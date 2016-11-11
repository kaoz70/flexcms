var yimaPage = function() {
    var initilize = function() {
        //Line Chart
        var randomScalingFactor = function() { return Math.round(Math.random() * 100) };
        var lineChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(39,41,44,0.2)",
                    strokeColor: "rgba(39,41,44,1)",
                    pointColor: "rgba(39,41,44,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(39,41,44,1)",
                    data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                }
            ]

        }

        //Bar Chart
        var barChartData = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    fillColor: "rgba(220,220,220,0.5)",
                    strokeColor: "rgba(220,220,220,0.8)",
                    highlightFill: "rgba(220,220,220,0.75)",
                    highlightStroke: "rgba(220,220,220,1)",
                    data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                },
                {
                    fillColor: "rgba(39,41,44,0.5)",
                    strokeColor: "rgba(39,41,44,0.8)",
                    highlightFill: "rgba(39,41,44,0.75)",
                    highlightStroke: "rgba(39,41,44,1)",
                    data: [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), randomScalingFactor()]
                }
            ]

        }

        //Polar Area
        var polarData = [
            {
                value: 300,
                color: "#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            },
            {
                value: 40,
                color: "#949FB1",
                highlight: "#A8B3C5",
                label: "Grey"
            },
            {
                value: 120,
                color: "#4D5360",
                highlight: "#616774",
                label: "Dark Grey"
            }
        ];

        //Radar Chart
        var radarChartData = {
            labels: ["Eating", "Drinking", "Sleeping", "Designing", "Coding", "Cycling", "Running"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 90, 81, 56, 55, 40]
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(39,41,44,0.2)",
                    strokeColor: "rgba(39,41,44,1)",
                    pointColor: "rgba(39,41,44,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(39,41,44,1)",
                    data: [28, 48, 40, 19, 96, 27, 100]
                }
            ]
        };

        //Pie Chart


        var pieData = [
            {
                value: 300,
                color: "#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            }, {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            }, {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            }, {
                value: 40,
                color: "#949FB1",
                highlight: "#A8B3C5",
                label: "Grey"
            }, {
                value: 120,
                color: "#4D5360",
                highlight: "#616774",
                label: "Dark Grey"
            }
        ];

        //Dougnut Chart
        var doughnutData = [
            {
                value: 300,
                color: "#F7464A",
                highlight: "#FF5A5E",
                label: "Red"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Green"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Yellow"
            },
            {
                value: 40,
                color: "#949FB1",
                highlight: "#A8B3C5",
                label: "Grey"
            },
            {
                value: 120,
                color: "#4D5360",
                highlight: "#616774",
                label: "Dark Grey"
            }
        ];


        window.onload = function() {
            window.myLine = new Chart(document.getElementById("line-chart").getContext("2d")).Line(lineChartData, {
                responsive: true
            });
            window.myBar = new Chart(document.getElementById("bar-chart").getContext("2d")).Bar(barChartData, {
                responsive: true
            });

            window.myPolarArea = new Chart(document.getElementById("polar-area-chart").getContext("2d")).PolarArea(polarData, {
                responsive: true
            });

            window.myRadar = new Chart(document.getElementById("radar-chart").getContext("2d")).Radar(radarChartData, {
                responsive: true
            });

            window.myPie = new Chart(document.getElementById("pie-chart").getContext("2d")).Pie(pieData, {
                responsive: true
            });

            window.myDoughnut = new Chart(document.getElementById("doughnut-chart").getContext("2d")).Doughnut(doughnutData, { responsive: true });
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