/*global $, system */

/**
 * Created by Miguel on 25/11/2014.
 */

var search = {

    timer : null,
    submitButton : null,
    resultContainer : null,
    input : null,
    keyTimeout : 500,
    slideSpeed: 400,
    initialSearchString: '',
    language : '',
    url : '',

    init : function (main_el) {

        "use strict";

        search.input = main_el.find('input[type="text"]');

        search.input.click(search.clickListener);
        search.input.blur(search.blurListener);
        search.input.keyup(search.keyupListener);
        search.submitButton = main_el.find('input[type="submit"]');
        search.resultContainer = main_el.find('.searchResult');

        search.submitButton.click(search.hideResultBox);

        $('html').click(search.hideResultBox);

        main_el.click(function (event) {
            event.stopPropagation();
        });

        search.initialSearchString = search.input.val();

        search.language = system.lang;
        search.url = system.base_url;
    },

    clickListener : function (event) {

        "use strict";

        var element = $(event.target),
            value = element.val();

        if (value === search.initialSearchString) {
            element.val('');
        }

    },

    blurListener : function (event) {

        "use strict";

        var element = $(event.target),
            value = element.val();

        if (value === '') {
            element.val(search.initialSearchString);
            search.hideResultBox();
        }

    },

    keyupListener : function (event) {

        "use strict";

        clearTimeout(search.timer);
        search.timer = setTimeout(function () {
            search.start($(event.target).val());
        }, search.keyTimeout);
    },

    start : function (value) {

        "use strict";

        search.submitButton.addClass('loading');

        var jsonRequest = $.ajax({
            url: search.url + 'search',
            data: {
                'query' : value,
                'language' : search.language
            }
        })
            .done(function (result) {
                search.submitButton.removeClass('loading');
                search.submitButton.addClass('cancel');
                search.generateResultBox(result);
            });

    },

    generateResultBox : function (result) {

        "use strict";

        search.resultContainer
            .empty()
            .append(result);

        search.resultContainer.slideDown(search.slideSpeed);

    },

    hideResultBox : function () {

        "use strict";

        search.resultContainer.slideUp(search.slideSpeed);
        search.submitButton.removeClass('loading');
        search.submitButton.removeClass('cancel');

        search.input.val(search.initialSearchString);
    }

};