var yimaPage = function() {
    var initilize = function() {
        $('.summernote').each(function() {
            $(this).summernote({
                height: 300,
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

        //CKEditor
        CKEDITOR.disableAutoInline = true;

        CKEDITOR.replace('cKEditor-normal', {
            language: 'es',
            uiColor: '#F7B42C',
            height: 300,
            toolbarCanCollapse: true
        });

        //CKEditor Inline
        CKEDITOR.inline('cKEditor-inline');
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