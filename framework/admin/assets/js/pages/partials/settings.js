(function() {
    //------------------------------------------------------------------
    //[1. Close Settings Bar]
    //--
    $('.sidebar.form .header-close').on('click', function() {
        $('.sidebar.form').addClass('collapsed');
    });

    //------------------------------------------------------------------
    //[2. Sidebar Menu Compact Action]
    //--
    $('#settings-menu-compact-checkbox').on('change', function() {
        $('.sidebar.menu').removeClass('collapsed');
        $('.sidebar.menu').removeClass('over compact');
        if (this.checked) {
            if (Modernizr.mq('(max-width: 600px)'))
                $('.sidebar.menu').addClass('over compact');
            else
                $('.sidebar.menu').addClass('compact');
        }
    });

    //------------------------------------------------------------------
    //[3. Skins]
    //--
    var skin = yima.getCookie("yima-skin");
    if (skin != 'undefined' && skin != null && skin != "") {
        $('.sidebar.form .settingsbar .colors-list > li').removeClass('active-color');
        $('*[data-skin=' + skin + ']').addClass('active-color');
    }

    $('.sidebar.form .settingsbar .colors-list > li').on('click', function() {
        var skin = $(this).data('skin');

        if ($('#settings-menu-skin-redraw').is(':checked')) {
            if (skin !== "")
                yima.addCookie('yima-skin', skin, 30);
            else
                yima.removeCookie('yima-skin');

            window.location.reload(false);
        } else {
            $('#link-skin').remove();
            if (skin !== "")
                $("<link/>", {
                    rel: "stylesheet",
                    type: "text/css",
                    id: 'link-skin',
                    href: "/assets/css/less/skins/" + skin + ".css"
                }).appendTo("head");
            $('.sidebar.form .settingsbar .colors-list > li').removeClass('active-color');
            $(this).addClass('active-color');
        }
    });

    //------------------------------------------------------------------
    //[4. Right to Left]
    //--

    $('#settings-menu-rtl').on('change', function() {
        //Simulates Page Loading
        $("body").append("<div class='animsition-loading' style='z-index:9999;'>" + yima.loadingMarkUp + "</div>").delay(1000).queue(function(next) {
            $(".animsition-loading").remove();
            next();
        });
        if (this.checked) {
            $("<link/>", {
                rel: "stylesheet",
                type: "text/css",
                id: 'link-bootstrap-rtl',
                href: "/assets/css/bootstrap.rtl.min.css"
            }).insertAfter("#link-bootstrap");
            $('#link-app').attr('href', '/assets/css/app.rtl.min.css');
            $(".pull-right, .pull-left").toggleClass("pull-right pull-left");
            $(".navbar-right, .navbar-left").toggleClass("navbar-right navbar-left");
        } else {
            $('#link-bootstrap-rtl').remove();
            $('#link-app').attr('href', '/assets/css/app.min.css');
            $(".pull-left, .pull-right").toggleClass("pull-left pull-right");
            $(".navbar-left, .navbar-right").toggleClass("navbar-left navbar-right");
        }
    });


    //------------------------------------------------------------------
    //[5. Add Scroll]
    //--
    $('.settingsbar .sidebar-body').slimscroll({
        touchScrollStep: yima.touchScrollSpeed,
        height: $(window).height() - 125,
        position: 'right',
        size: '2px',
        color: yima.primary
    });
})();