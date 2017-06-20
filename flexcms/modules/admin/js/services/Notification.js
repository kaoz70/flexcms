/**
 * @ngdoc service
 * @name App:Notification
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Notification', function() {
        const options = {
            layout: 'other',
            effect: 'thumbslider',
            position: 'topright',
        };

        this.show = (type, message) => {
            let icon;

            if (message === undefined || message === '') {
                return;
            }

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

            options.message = `<div class="ns-thumb"><i class="${icon}"></i></div><div class="ns-content"><p>${message}</p></div>`;
            options.type = type;

            const notification = new NotificationFx(options);

            notification.show();
        };
    });
