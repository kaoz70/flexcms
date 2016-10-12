//------------------------------------------------------------------
//[App Main JavaScript Structure]

//Project:	Yima Admin App
//Version:	1.0.0
//Last change:	12/12/15 [Changelog: (http://www.IssatisLab.com)]
//Implemented By:	IssatisLab (http://www.IssatisLab.com)


//[Table of contents]

// Global Variables
// Skin Initialization
// Page Ready Initialization
//    Page Transition Init
//    Navbar Shadow on Scroll
// Forms
//    Help Form
//    Settings Form
//    Chat Form
//    Search Form
// Panel
//    Dispose Panel
//    Collapse Panel
//    Maximize Panel
// Header Actions
// Tooltip Init
// Sidebar Menu
//    SlimScroll Init
//    Sidebar Menu Click Handle
//    Sidebar Menu Collapse (Header Action)
// Sidebar Form (Header Action)
// Colors
//    Hex to RGBA
//    Get Color from String
// Arrays
//    String to Array 
// Cookies
//    Add or Update Cookies
//    Get Cookies
//    Remove Cookies
//-------------------------------------------------------------------

var yima = function() {

    var isAngular = true;
    var isAspMvc = false;
    var _default = '#27292c';
    var primary = '#29c7ca';
    var danger = '#cd4237';
    var success = '#1dbc9c';
    var warning = '#FFC107';
    var info = '#34b5dc';
    var inverse = '#55606e';

    var touchScrollSpeed = 80;

    var loadingMarkUp =
        "<div class='loading'>\
            <div class='loader-circle'>\
            </div>\
            <div class='loader-line-mask'>\
                <div class='loader-line'>\
                </div>\
            </div>\
            <div class='glow'>\
            </div>\
            <div class='logo' style='background-image:url(\"" + getAssetPath("assets/img/logo.png") + "\")'>\
            </div>\
        </div>"

    function hextoRgba(hex, opacity) {
        hex = hex.replace('#', '');
        var r = parseInt(hex.substring(0, 2), 16);
        var g = parseInt(hex.substring(2, 4), 16);
        var b = parseInt(hex.substring(4, 6), 16);

        var result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
        return result;
    }

    function getcolor(colorString) {
        switch (colorString) {
        case ("default"):
            return _default;
        case ("primary"):
            return primary;
        case ("danger"):
            return danger;
        case ("warning"):
            return warning;
        case ("info"):
            return info;
        case ("success"):
            return success;
        case ("inverse"):
            return inverse;
        default:
            return colorString;
        }
    }

    function stringToIntArray(str) {
        if (str.indexOf(':') > -1)
            return str.split(",");

        var array = str.split(",");
        for (var i = 0; i < array.length; i++) {
            array[i] = +array[i];
        }
        for (var i = 0; i < array.length; i++) {
            array[i] = parseInt(array[i], 10);
        }
        return array;
    }

    function toggleFormSidebar(formName) {
        if ($('.sidebar.form').hasClass('collapsed'))
            $('.sidebar.form').removeClass('collapsed');
        else if ($('.sidebar.form').data('form') == formName)
            $('.sidebar.form').addClass('collapsed');

        $('.sidebar.form').data('form', formName);
    }

    function addCookie(name, value, days) {
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            var expires = "; expires=" + date.toGMTString();
        } else var expires = "";
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEq = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEq) == 0) return c.substring(nameEq.length, c.length);
        }
        return null;
    }

    function removeCookie(name) {
        addCookie(name, "", -1);
    }

    function getAssetPath(assetPath) {
        if (isAspMvc) {
            return "/" + assetPath;
        } else {
            return "framework/admin/" + assetPath;
        }
    }

    return {
        //------------------------------------------------------------------
        //[Global Variables]
        //--
        isAngular: function() {
            return isAngular
        },
        
        isAspMvc: function() {
            return isAspMvc
        },

        _default: function() {
            return _default;
        },

        primary: function() {
            return primary;
        },

        danger: function() {
            return danger;
        },

        success: function() {
            return success;
        },

        warning: function() {
            return warning;
        },

        info: function() {
            return info;
        },

        inverse: function() {
            return inverse;
        },

        skinColors: function() {
            return {
                'default': '#29c7ca',
                'light-green': '#5bd2b8',
                'green': '#2cc491',
                'dark-green': '#00828c',
                'light-blue': '#26c1ff',
                'blue': '#0c9fd6',
                'velvet': '#8c5dad',
                'pink': '#e076ed',
                'red': '#ff5b57',
                'orange': '#f86e49',
                'light-orange': '#f07f7f',
                'yellow': '#ffd967'
            };
        },

        touchScrollSpeed: function() {
            return touchScrollSpeed;
        },

        loadingMarkUp: function() {
            return loadingMarkUp;
        },

        init: function() {
            //------------------------------------------------------------------
            //[Skin Initialization]
            //--
            var skin = this.getCookie("yima-skin");
            if (skin != 'undefined' && skin != null && skin != "") {
                $("<link/>", {
                    rel: "stylesheet",
                    type: "text/css",
                    id: 'link-skin',
                    href: "/assets/css/less/skins/" + this.getCookie("yima-skin") + ".css"
                }).appendTo("head");
                primary = this.skinColors[skin];
            }

            //------------------------------------------------------------------
            //[Page Transition Init]
            //--
            $(".animsition").animsition({
                inClass: 'fade-in',
                outClass: 'fade-out',
                inDuration: 1500,
                outDuration: 800,
                linkElement: '.sidebar-menu .menu a[href]:not([target="_blank"]):not([href^=#]):not([href=""])',
                loading: true,
                loadingParentElement: 'body',
                loadingClass: 'animsition-loading',
                loadingInner: loadingMarkUp,
                timeout: false,
                timeoutCountdown: 5000,
                onLoadEvent: true,
                browser: ['animation-duration', '-webkit-animation-duration'],
                overlay: false,
                overlayClass: 'animsition-overlay-slide',
                overlayParentElement: 'body',
                transition: function(url) { window.location.href = url; }
            });

            //------------------------------------------------------------------
            //[Navbar Shadow on Scroll]
            //--
            $('.content-body').scroll(function() {
                if ($('.content-body').scrollTop() > 25) {
                    $('.content-nav-navbar').addClass('navbar-shadow');
                } else {
                    $('.content-nav-navbar').removeClass('navbar-shadow');
                }
            });

            //------------------------------------------------------------------
            //[Help Form (Header Action)]
            //--
            $('#action-help a').click(function() {
                $(".sidebar.form").load("/Partials/Help.html");
                toggleFormSidebar('Help');

                $('#action-stretch-menu').removeClass('open');
            });

            //------------------------------------------------------------------
            //[Settings Form (Header Action)]
            //--
            $('#action-settings a').click(function() {
                $(".sidebar.form").load("/Partials/Settings.html");
                toggleFormSidebar('Settings');
                $('#action-stretch-menu').removeClass('open');
            });

            //------------------------------------------------------------------
            //[Chat Form (Header Action)]
            //--
            $('#action-chat a').click(function() {
                $(".sidebar.form").load("/Partials/Chat.html");
                toggleFormSidebar('Chat');
                $('#action-stretch-menu').removeClass('open');
            });

            //------------------------------------------------------------------
            //[Search Form (Header Action)]
            //--
            $('#action-search a').click(function() {
                $(".sidebar.form").load("/Partials/Search.html");
                toggleFormSidebar('Search');
                $('#action-stretch-menu').removeClass('open');
            });

            //------------------------------------------------------------------
            //[Panel Dispose]
            //--
            $('.panel-tools .tools-action[data-toggle="dispose"]').on("click", function(event) {
                //Variables
                var disposeInterval = 300;

                event.preventDefault();

                //Get The Panel
                var panel = $(this).parents(".panel").eq(0);

                //Dispose Panel
                panel.hide(disposeInterval, function() {
                    panel.remove();
                });
            });

            //------------------------------------------------------------------
            //[Panel Collpase]
            //--
            $('.panel-tools .tools-action[data-toggle="collapse"]').on("click", function(event) {
                //Variables
                var slideDownInterval = 300;
                var slideUpInterval = 200;
                var downIcon = "pe-7s-angle-down";
                var upIcon = "pe-7s-angle-up";


                event.preventDefault();

                //Get The Panel
                var panel = $(this).parents(".panel").eq(0);
                var body = panel.find(".panel-body");
                var icon = $(this).find("i");

                //Expand Panel
                if (panel.hasClass("collapsed")) {
                    if (icon) {
                        icon.addClass(upIcon).removeClass(downIcon);
                    }
                    panel.removeClass("collapsed");
                    body.slideUp(0, function() {
                        body.slideDown(slideDownInterval);
                    });
                    //Collpase Panel
                } else {
                    if (icon) {
                        icon.addClass(downIcon)
                            .removeClass(upIcon);
                    }
                    body.slideUp(slideUpInterval, function() {
                        panel.addClass("collapsed");
                    });
                }
            });

            //------------------------------------------------------------------
            //[Panel Maximize]
            //--
            $('.panel-tools .tools-action[data-toggle="maximize"]').on("click", function(event) {
                event.preventDefault();

                //Get The Panel
                var panel = $(this).parents(".panel").eq(0);
                var icon = $(this).find("i").eq(0);
                var compressIcon = "pe-7s-power";
                var expandIcon = "pe-7s-expand1";

                //Minimize Panel
                if (panel.hasClass("maximized")) {
                    if (icon) {
                        icon.addClass(expandIcon).removeClass(compressIcon);
                    }
                    panel.removeClass("maximized");
                    panel.find(".panel-body").css("height", "auto");

                    //Maximize Panel
                } else {
                    if (icon) {
                        icon.addClass(compressIcon).removeClass(expandIcon);
                    }
                    panel.addClass("maximized");
                    var windowHeight = $(window).height();
                    var headerHeight = panel.find(".panel-heading").height();
                    panel.find(".panel-body").height(windowHeight - headerHeight);
                }
            });

            //------------------------------------------------------------------
            //[Header Actions]
            //--
            $('#action-stretch-menu').on('click', function(e) {
                $('#action-stretch-menu').toggleClass('open');
            });

            //------------------------------------------------------------------
            //[Tooltip Init]
            //--
            $("[data-toggle=tooltip]").tooltip({
                html: true
            });
        },

        sidebarInit: function() {
            $(document).ready(function() {
                //------------------------------------------------------------------
                //[SlimScroll Init]
                //--
                $('.sidebar-menu .menu').slimscroll({
                    touchScrollStep: touchScrollSpeed,
                    height: $(window).height() - 175,
                    position: 'left',
                    size: '3px',
                    color: primary
                })

                if (isAngular === false) {
                    $('.content-body').slimscroll({
                        touchScrollStep: touchScrollSpeed,
                        height: $(window).height() - 125,
                        width: '100%',
                        position: 'right',
                        size: '3px',
                        color: primary
                    });
                }
            });

            //------------------------------------------------------------------
            //[Sidebar Menu Click Handle]
            //--
            $('.sidebar-menu .menu > li > a').on('click', function(e) {
                e.preventDefault();
                var target = e.target;
                if (!$(target).is('li'))
                    target = $(target).closest('li');
                if ($(target).find(" > a").attr('href') !== undefined && !target.hasClass('active')) {
                    window.location.href = $(target).find(" > a").attr('href');
                    return;
                }
                if (($('.sidebar.menu').hasClass('compact') || Modernizr.mq('(max-width: 1050px)')) && !$('.sidebar.menu').hasClass('over')) {
                    target.toggleClass('compact-open');
                    return;
                }
                $('.sidebar-menu .menu > li').removeClass('open');
                target.addClass('open');
            });

            //------------------------------------------------------------------
            //[Sidebar Menu Collapse (Header Action)]
            //--
            $('#action-menu-collapse a').click(function() {
                if (Modernizr.mq('(max-width: 600px)'))
                    $('.sidebar.menu').toggleClass('over');
                else
                    $('.sidebar.menu').toggleClass('collapsed');

                $('#action-stretch-menu').removeClass('open');
            });
        },

        //------------------------------------------------------------------
        //[Sidebar Form (Header Action)]
        //--
        toggleFormSidebar: toggleFormSidebar,

        //------------------------------------------------------------------
        //[Colors]
        //--

        //------------------------------------------------------------------
        //[Hex to RGBA]
        //--
        hextoRgba: function(hex, opacity) {
            return hextoRgba(hex, opacity);
        },

        //------------------------------------------------------------------
        //[Get Color from String]
        //--
        getcolor: function(colorString) {
            return getcolor(colorString);
        },

        //------------------------------------------------------------------
        //[Arrays]
        //--

        //------------------------------------------------------------------
        //[String to Array]
        //--
        stringToIntArray: function(str) {
            return stringToIntArray(str);
        },

        //------------------------------------------------------------------
        //[Cookies]
        //--

        //------------------------------------------------------------------
        //[Add or Update Cookies]
        //--
        addCookie: function(name, value, days) {
            return addCookie(name, value, days);
        },

        //------------------------------------------------------------------
        //[Get Cookies]
        //--
        getCookie: function(name) {
            return getCookie(name);
        },

        //------------------------------------------------------------------
        //[Remove Cookies]
        //--
        removeCookie: function(name) {
            return removeCookie(name);
        },

        getAssetPath: function (assetPath) {
            return getAssetPath(assetPath);
        },
    };
}();

jQuery(document).ready(function() {
    if (yima.isAngular() === false) {
        yima.init();
    }
    yima.sidebarInit();
});