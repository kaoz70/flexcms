/**
 * @ngdoc service
 * @name App:Notification
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Notification', function(){

        var options = {
            layout: 'other',
            //ttl: 2000,
            effect: 'thumbslider',
            position: 'topright'
        };

        this.show = function(type, message) {

            if(message === undefined || message === '') {
                return;
            }

            var icon;

            switch (type) {
                case 'error':
                    icon = 'pe-7s-shield';
                    break;
                case 'success':
                    icon = 'pe-7s-check';
                    break;
                case 'warning':
                    icon = 'pe-7s-attention';
                    break;
                default:
                    icon = 'pe-7s-info';
            }

            options.message = '<div class="ns-thumb"><i class="' + icon + '"></i></div><div class="ns-content"><p>' + message + '</p></div>';
            options.type = type;

            var notification = new NotificationFx(options);

            notification.show();

        };

});

