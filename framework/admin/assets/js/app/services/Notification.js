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
        ttl: 2000,
        effect: 'scale',
        position: 'topright'
    };

    this.show = function(type, message) {

        options.message = message;
        options.type = type;

        var notification = new NotificationFx(options);

        notification.show();

    };

});

