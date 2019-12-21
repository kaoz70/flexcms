/**
 * @ngdoc directive
 * @name app:EdtorDirective
 *
 * @description
 *
 *
 * @restrict A
 * */
angular.module('app')
    .directive('editor', (BASE_PATH) => ({
        restrict: 'E',
        templateUrl: `${BASE_PATH}admin/Editor`,
        scope: {
            contentModel: '=',
            editorInit: '=',
        },
        link: {
            pre(scope) {
                // Code editor options
                scope.editorOptions = {
                    mode: 'text/html',
                    lineNumbers: true,
                    theme: 'rubyblue',
                    autoCloseTags: true,
                    indentAuto: true,
                    autoRefresh: true,
                    inline: true,
                    matchTags: {
                        bothTags: true,
                    },
                    foldGutter: true,
                    gutters: ['CodeMirror-linenumbers', 'CodeMirror-foldgutter'],
                    extraKeys: { 'Ctrl-Space': 'autocomplete' },
                };

                // TinyMCE Options
                scope.tinymceOptions = {
                    toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                    init_instance_callback(ed) {
                        scope.editorInit = true;
                    },
                };
            },
        },

    }));
