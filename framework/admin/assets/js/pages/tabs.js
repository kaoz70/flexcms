var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            var markerMap = new GMaps({
                el: '#map',
                lat: -12.053333,
                lng: -77.028333,
            });
            markerMap.addMarker({
                lat: -12.053333,
                lng: -77.028333,
                title: 'Lima',
                details: {
                    database_id: 42,
                    author: 'HPNeo'
                },
                click: function(e) {
                    if (console.log)
                        console.log(e);
                },
                mouseover: function(e) {
                    if (console.log)
                        console.log(e);
                }
            });
            var styles = [
                {
                    stylers: [
                        { lightness: -8 },
                        { saturation: -100 },
                        { gamma: 1.18 }
                    ]
                }, {
                    featureType: "road",
                    elementType: "geometry",
                    stylers: [
                        { lightness: 100 },
                        { visibility: "simplified" }
                    ]
                }, {
                    featureType: "road",
                    elementType: "labels",
                    stylers: [
                        { visibility: "off" }
                    ]
                }
            ];

            markerMap.addStyle({
                styledMapName: "Styled Map",
                styles: styles,
                mapTypeId: "map_style"
            });

            markerMap.setStyle("map_style");
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