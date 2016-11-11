var yimaPage = function() {
    var initilize = function() {
        $('.mail-container .message-list li').click(function(e) {
            var item = $(this),
                target = $(e.target);

            if (target.is(':checkbox')) {
                item.toggleClass('selected');
            } else if (!target.parent().is('label')) {
                e.preventDefault();
                $('.message-view').toggleClass('collapsed');
            }
        });

        $('#message-close').click(function(e) {
            $('.message-view').toggleClass('collapsed');
        });
        $('#new-message, #discard-message, #send-message').click(function(e) {
            e.preventDefault();
            $('.message-compose').toggleClass('collapsed');
            $('#compose-buttons').toggleClass('hidden');
            $('#list-buttons').toggleClass('hidden');
        });

        //initial Summernote
        $('.summernote').each(function() {
            $(this).summernote({
                height: 600,
                airMode: $(this).data('airmode') ? $(this).data('airmode') : false,
                airPopover: [
                    ["style", ["style"]],
                    ['color', ['color']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['para', ['ul', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture']]
                ],
                toolbar: [
                    ["style", ["style"]],
                    ["style", ["bold", "italic", "underline", "clear"]],
                    ["fontsize", ["fontsize"]],
                    ["color", ["color"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                    ["table", ["table"]],
                    ['view', ['codeview']]
                ]
            });
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