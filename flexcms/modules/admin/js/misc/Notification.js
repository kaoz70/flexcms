/**
 * @ngdoc service
 * @name App:Notification
 *
 * @description
 *
 *
 * */
angular.module('app')
    .service('Notification', function ($injector) {
        this.show = (type, message) => {
            const toastr = $injector.get('toastr');

            if (message === undefined || message === '') {
                return;
            }

            switch (type) {
            case 'error':
                toastr.error(message);
                break;
            case 'success':
                toastr.success(message);
                break;
            case 'warning':
                toastr.warning(message);
                break;
            default:
                toastr.info(message);
            }
        };
    });
