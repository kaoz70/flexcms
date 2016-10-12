(function() {
    //------------------------------------------------------------------
    //[1. Close Chat Bar]
    //--
    $('#action-collapse a').on('click', function() {
        $('.sidebar.form').addClass('collapsed');
    });

    //------------------------------------------------------------------
    //[2. Contacts/Messages]
    //--
    $('.chatbar .chatbar-contacts .contact').on('click', function(e) {
        $('.chatbar .chatbar-contacts').hide();
        $('.chatbar .chatbar-messages').show();
    });

    $('.chatbar .chatbar-messages #action-back').on('click', function(e) {
        $('.chatbar .chatbar-contacts').show();
        $('.chatbar .chatbar-messages').hide();
    });

    $('.chatbar-contacts .contacts-list').slimscroll({
        touchScrollStep: yima.touchScrollSpeed,
        height: $(window).height() - 166,
        position: 'right',
        size: '2px',
        color: yima.primary
    });

    $('.chatbar-messages .messages-list').slimscroll({
        touchScrollStep: yima.touchScrollSpeed,
        height: $(window).height() - 190,
        position: 'right',
        size: '2px',
        color: yima.primary
    });
})();