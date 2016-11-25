(function() {
    'use strict';

    //Panel Dispose
    angular
        .module('app')
        .directive('panelDispose', panelDispose);

    function panelDispose(WindowFactory) {
        return {
            restrict: 'E',
            template: '<a class="tools-action" href=""><md-icon>close</md-icon></a>',
            link: function(scope, el, attr) {
                el.on('click', function() {
                    WindowFactory.remove(scope);
                });
            }
        };
    };

    //Panel Collapse
    angular
        .module('app')
        .directive('panelCollapse', panelCollapse);

    function panelCollapse() {
        return {
            restrict: 'E',
            template: '<a class="tools-action" href="" data-toggle="collapse"><i class="pe-7s-angle-up"></i></a>',
            link: function(scope, el, attr) {
                el.on('click', function() {
                    //Variables
                    var slideDownInterval = 300;
                    var slideUpInterval = 200;
                    var downIcon = "pe-7s-angle-down";
                    var upIcon = "pe-7s-angle-up";


                    event.preventDefault();

                    //Get The Panel
                    var panel = el.parents(".panel").eq(0);
                    var body = panel.find(".panel-body");
                    var icon = el.find("i");

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
            }
        };
    };


    //Panel Maximize
    angular
        .module('app')
        .directive('panelMaximize', panelMaximise);

    function panelMaximise() {
        return {
            restrict: 'E',
            template: '<a class="tools-action" href="" data-toggle="maximize"><i class="pe-7s-expand1"></i></a>',
            link: function(scope, el, attr) {
                el.on('click', function() {
                    event.preventDefault();

                    //Get The Panel
                    var panel = el.parents(".panel").eq(0);
                    var icon = el.find("i").eq(0);
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
            }
        };
    };


}());