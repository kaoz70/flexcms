window.addEvent('domready', function () {
    "use strict";

    //Periodically poll every hour for the CSRF token and set it accordingly
    var poll_csrf = function(){
        new Request({
            url : 'admin/ajax/get_csrf',
            onSuccess : function (result) {
                //Set any inputs with the correct token
                $$('input[name="csrf_test"]').each(function (item) {
                    item.set('value', result);
                });
            }
        }).get();
    };

    poll_csrf();
    poll_csrf.periodical(60000 * 60);

});