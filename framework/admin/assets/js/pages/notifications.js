var yimaPage = function() {
    var initilize = function() {
        /*Handles Popovers*/
        var popovers = $('[data-toggle=popover]');
        $.each(popovers, function() {
            $(this)
                .popover({
                    html: true,
                    template: '<div class="popover ' + $(this)
                        .data("class") +
                        '"><div class="arrow"></div><h3 class="popover-title ' +
                        $(this)
                        .data("titleclass") + '">Popover right</h3><div class="popover-content"></div></div>'
                });
        });

        var hoverpopovers = $('[data-toggle=popover-hover]');
        $.each(hoverpopovers, function() {
            $(this)
                .popover({
                    html: true,
                    template: '<div class="popover ' + $(this)
                        .data("class") +
                        '"><div class="arrow"></div><h3 class="popover-title ' +
                        $(this)
                        .data("titleclass") + '">Popover right</h3><div class="popover-content"></div></div>',
                    trigger: "hover"
                });
        });


        /*Notifications*/

        /*Scale*/
        $('#scale').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>This is just a simple notice. Everything is in order and this is a <a href="#">simple link</a>.</p>',
                layout: 'growl',
                effect: 'scale',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Jelly*/
        $('#jelly').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>Hello there! I\'m a classic notification but I have some elastic jelliness thanks to <a href="http://bouncejs.com/">bounce.js</a>. </p>',
                layout: 'growl',
                effect: 'jelly',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Slide*/
        $('#slide').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>This notification has slight elasticity to it thanks to <a href="http://bouncejs.com/">bounce.js</a>.</p>',
                layout: 'growl',
                effect: 'slide',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Genie*/
        $('#genie').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>Your preferences have been saved successfully. See all your settings in your <a href="#">profile overview</a>.</p>',
                layout: 'growl',
                effect: 'genie',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Flip*/
        $('#flip').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>Your preferences have been saved successfully. See all your settings in your <a href="#">profile overview</a>.</p>',
                layout: 'attached',
                effect: 'flip',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Bouncy Flip*/
        $('#bouncyflip').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<span class="icon pe-7s-date"></span><p>The event was added to your calendar. Check out all your events in your <a href="#">event overview</a>.</p>',
                layout: 'attached',
                effect: 'bouncyflip',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Slide Top*/
        $('#slidetop').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<span class="icon pe-7s-mail"></span><p>You have some interesting news in your inbox. Go <a href="#">check it out</a> now.</p>',
                layout: 'bar',
                effect: 'slidetop',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Expanding Loader*/
        $('#exploader').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<span class="icon pe-7s-settings"></span><p>Your preferences have been saved successfully. See all your settings in your <a href="#">profile overview</a>.</p>',
                layout: 'bar',
                effect: 'exploader',
                ttl: 9000000,
                type: $('input:radio[name=color-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Box Spinner*/
        $('#spinner').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<p>I am using a beautiful spinner from <a href="http://tobiasahlin.com/spinkit/">SpinKit</a></p>',
                layout: 'other',
                effect: 'boxspinner',
                ttl: 9000,
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
        });

        /*Thumb Slider*/
        $('#thumb').on('click', function(e) {
            e.preventDefault();
            var notification = new NotificationFx({
                message: '<div class="ns-thumb"><img src="' + yima.getAssetPath("assets/img/avatars/jsa.jpg") + '"/></div><div class="ns-content"><p><a href="#">Zoe Moulder</a> accepted your invitation.</p></div>',
                layout: 'other',
                ttl: 6000,
                effect: 'thumbslider',
                type: $('input:radio[name=color-radio]:checked').val(),
                position: $('input:radio[name=position-radio]:checked').val(),
                onClose: function() {
                }
            });
            notification.show();
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