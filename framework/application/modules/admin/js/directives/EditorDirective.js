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
    .directive('editor', function (BASE_PATH) {
        return {
            restrict: 'E',
            templateUrl: BASE_PATH + 'admin/Editor',
            scope: {
                contentModel: '=',
                editorInit: '='
            },
            link: {
                pre: function prelink(scope){

                    //Code editor options
                    scope.editorOptions = {
                        mode : "text/html",
                        lineNumbers: true,
                        theme: 'rubyblue',
                        autoCloseTags: true,
                        indentAuto: true,
                        autoRefresh: true,
                        matchTags: {
                            bothTags: true
                        },
                        foldGutter: true,
                        gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
                        extraKeys: {"Ctrl-Space": "autocomplete"}
                    };

                    //Init the editor
                    scope.tinymceOptions = {
                        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                        setup: function (ed) {
                            ed.on('init', function() {
                                scope.editorInit = true;
                            });
                        }
                    };

                }
            }

        };
});
