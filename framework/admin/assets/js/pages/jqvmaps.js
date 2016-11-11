var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            $('#germany').vectorMap({
                map: 'germany_en',
                backgroundColor: '#fff',
                borderColor: '#fff',
                borderOpacity: 0.25,
                borderWidth: 2,
                color: yima.primary(),
                hoverColor: yima.primary(),
                hoverOpacity: 0.5,
                onRegionClick: function(element, code, region) {
                    var message = 'You clicked "'
                        + region
                        + '" which has the code: '
                        + code.toUpperCase();

                    alert(message);
                }
            });

            $('#usa').vectorMap({
                map: 'usa_en',
                enableZoom: true,
                showTooltip: true,
                selectedRegion: 'MO',
                backgroundColor: '#fff',
                borderColor: '#fff',
                borderOpacity: 0.25,
                borderWidth: 2,
                color: yima._default(),
                hoverColor: yima._default(),
                hoverOpacity: 0.5,
                selectedColor: yima.warning(),
            });

            $('#russia').vectorMap({
                map: 'russia_en',
                backgroundColor: '#fff',
                color: '#fafafa',
                hoverOpacity: 0.7,
                selectedColor: yima.warning(),
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial'
            });

            $('#europe').vectorMap({
                map: 'europe_en',
                enableZoom: false,
                showTooltip: false,
                backgroundColor: '#fff',
                borderColor: '#fff',
                borderOpacity: 0.25,
                borderWidth: 2,
                color: yima.danger(),
                hoverColor: yima.danger(),
                hoverOpacity: 0.5,
                selectedColor: yima.warning(),
            });
            $('#africa').vectorMap({
                map: 'africa_en',
                backgroundColor: '#fff',
                color: yima.success(),
                hoverOpacity: 0.7,
                selectedColor: yima._default(),
                enableZoom: true,
                showTooltip: true,
                normalizeFunction: 'polynomial'
            });
            $('#world').vectorMap({
                map: 'world_en',
                backgroundColor: '#fff',
                color: '#fafafa',
                hoverOpacity: 0.7,
                selectedColor: yima.warning(),
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: ['#C8EEFF', '#006491'],
                normalizeFunction: 'polynomial'
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