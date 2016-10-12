var yimaPage = function() {
    var initilize = function() {
        $(document).ready(function() {
            if (!Modernizr.mq('(max-width: 1600px)')) {
                $(".sidebar.form").load(yima.getAssetPath("Partials/Timeline.html"));
                yima.toggleFormSidebar('Timeline');
            }

            /* initialize the external events
            -----------------------------------------------------------------*/
            $('.calendar-event a').each(function() {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    title: $.trim($(this).text()), // use the element's text as the event title
                    stick: true // maintain when user navigates (see docs on the renderEvent method)
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });

            });
            /* initialize the calendar
            -----------------------------------------------------------------*/
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();

            $('#full-calendar').fullCalendar({
                header: {
                    left: 'title',
                    right: 'today month,agendaWeek,agendaDay prev,next'
                },
                handleWindowResize: true,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar
                drop: function() {
                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).parent().remove();
                    }
                },
                events: [
                    {
                        title: 'All Day Event',
                        start: new Date(y, m, 1),
                        borderColor: '#5db2ff'
                    },
                    {
                        title: 'Long Event',
                        start: new Date(y, m, d - 5),
                        end: new Date(y, m, d - 2),
                        borderColor: '#a0d468'
                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d - 3, 16, 0),
                        allDay: false,
                        borderColor: '#ffce55'

                    },
                    {
                        id: 999,
                        title: 'Repeating Event',
                        start: new Date(y, m, d + 4, 16, 0),
                        allDay: false,
                        borderColor: '#fb6e52'
                    },
                    {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false,
                        borderColor: '#e75b8d'
                    },
                    {
                        title: 'Awesome Plan',
                        start: new Date(y, m, d - 17, 2, 20),
                        end: new Date(y, m, d - 14, 14, 0),
                        allDay: false,
                        borderColor: '#a0d468'
                    },
                    {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        allDay: false,
                        borderColor: '#2dc3e8'
                    },
                    {
                        title: 'Birthday Party',
                        start: new Date(y, m, d + 1, 19, 0),
                        end: new Date(y, m, d + 1, 22, 30),
                        allDay: false,
                        borderColor: '#ed4e2a'
                    },
                    {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        url: 'http://google.com/'
                    }
                ]
            });

            $('.fc-toolbar').find('.fc-button-group').addClass('btn-group');
            $('.fc-toolbar').find('.fc-button').addClass('btn btn-inverse');
            $('.fc-toolbar').find('.fc-prev-button').html($('<span />').attr('class', 'fa fa-angle-left'));
            $('.fc-toolbar').find('.fc-next-button').html($('<span />').attr('class', 'fa fa-angle-right'));
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