var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {

            //Basic Map
            var basicmap = new GMaps({
                el: '#basic-map',
                lat: -12.043333,
                lng: -77.028333,
                zoomControl: true,
                zoomControlOpt: {
                    style: 'SMALL',
                    position: 'TOP_LEFT'
                },
                panControl: false,
                streetViewControl: false,
                mapTypeControl: false,
                overviewMapControl: false
            });


            //Geolocation Map
            var geolocationMap = new GMaps({
                el: '#geolocation-map',
                lat: -12.043333,
                lng: -77.028333
            });

            GMaps.geolocate({
                success: function(position) {
                    geolocationMap.setCenter(position.coords.latitude, position.coords.longitude);
                },
                error: function(error) {
                    alert('Geolocation failed: ' + error.message);
                },
                not_supported: function() {
                    alert("Your browser does not support geolocation");
                },
                always: function() {
                    //alert("Done!");
                }
            });

            //Marker
            var markerMap = new GMaps({
                el: '#marker-map',
                lat: -12.043333,
                lng: -77.028333
            });
            markerMap.addMarker({
                lat: -12.043333,
                lng: -77.03,
                title: 'Lima',
                details: {
                    database_id: 42,
                    author: 'HPNeo'
                },
                click: function(e) {
                    if (console.log)
                        console.log(e);
                    alert('You clicked in this marker');
                },
                mouseover: function(e) {
                    if (console.log)
                        console.log(e);
                }
            });
            markerMap.addMarker({
                lat: -12.042,
                lng: -77.028333,
                title: 'Marker with InfoWindow',
                infoWindow: {
                    content: '<p>HTML Content</p>'
                }
            });

            //Marker Clusterer
            var markerClustererMap = new GMaps({
                div: '#marker-clusterer-map',
                lat: -12.043333,
                lng: -77.028333,
                markerClusterer: function(markerClustererMap) {
                    return new MarkerClusterer(markerClustererMap);
                }
            });

            var lat_span = -12.035988012939503 - -12.050677786181573;
            var lng_span = -77.01528673535154 - -77.04137926464841;

            for (var i = 0; i < 100; i++) {
                var latitude = Math.random() * (lat_span) + -12.050677786181573;
                var longitude = Math.random() * (lng_span) + -77.04137926464841;

                markerClustererMap.addMarker({
                    lat: latitude,
                    lng: longitude,
                    title: 'Marker #' + i
                });
            }

            //Routes
            var routeMap = new GMaps({
                el: '#route-map',
                lat: -12.043333,
                lng: -77.028333
            });
            routeMap.drawRoute({
                origin: [-12.044012922866312, -77.02470665341184],
                destination: [-12.090814532191756, -77.02271108990476],
                travelMode: 'driving',
                strokeColor: '#131540',
                strokeOpacity: 0.6,
                strokeWeight: 6
            });

            //Advanced Routes
            advanceRouteMap = new GMaps({
                el: '#advance-route-map',
                lat: -12.043333,
                lng: -77.028333
            });
            advanceRouteMap.travelRoute({
                origin: [-12.044012922866312, -77.02470665341184],
                destination: [-12.090814532191756, -77.02271108990476],
                travelMode: 'driving',
                step: function(e) {
                    $('#instructions').append('<li>' + e.instructions + '</li>');
                    $('#instructions li:eq(' + e.step_number + ')').delay(450 * e.step_number).fadeIn(200, function() {
                        advanceRouteMap.drawPolyline({
                            path: e.path,
                            strokeColor: '#131540',
                            strokeOpacity: 0.6,
                            strokeWeight: 6
                        });
                    });
                }
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