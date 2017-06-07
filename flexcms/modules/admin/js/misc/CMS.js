/**
 * @ngdoc service
 * @name app:Color
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('CMS', function(){

        this.init = function() {

            $(".animsition").animsition({
                inClass: 'fade-in-left-sm',
                outClass: 'fade-out-left-sm',
                inDuration: 1500,
                outDuration: 800,
                linkElement: '.logout',
                loading: false,
                loadingParentElement: 'body',
                loadingClass: 'animsition-loading',
                timeout: false,
                timeoutCountdown: 5000,
                onLoadEvent: true,
                browser: ['animation-duration', '-webkit-animation-duration'],
                overlay: false,
                overlayClass: 'animsition-overlay-slide',
                overlayParentElement: 'body',
                transition: function(url) { window.location.href = url; }
            });

            $(window).load(function() {
                $(".loading").fadeOut("slow");
            });

        };

});

