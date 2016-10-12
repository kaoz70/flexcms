var easypiecharts = $('[data-toggle=easypiechart]');
$.each(easypiecharts, function () {
    var barColor = yima.getcolor($(this).data('barcolor')) || yima.primary(),
        trackColor = yima.getcolor($(this).data('trackcolor')) || false,
        scaleColor = yima.getcolor($(this).data('scalecolor')) || false,
        lineCap = $(this).data('linecap') || "round",
        lineWidth = $(this).data('linewidth') || 3,
        size = $(this).data('size') || 110,
        animate = $(this).data('animate') || false;

    $(this).easyPieChart({
        barColor: barColor,
        trackColor: trackColor,
        scaleColor: scaleColor,
        lineCap: lineCap,
        lineWidth: lineWidth,
        size: size,
        animate: animate
    });
    $(this).find('.percent').css('line-height', size + 'px');

});