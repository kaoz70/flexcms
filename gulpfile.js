var elixir = require('laravel-elixir'),
    gulp = require('gulp');

var Task = elixir.Task;

/**
 * Copies the fonts so that their paths are the ones from the CSS
 */
elixir.extend('copyfonts', function() {

    new Task('copyfonts', function() {
        return gulp.src(['./node_modules/material-design-icons/iconfont/*.{ttf,woff,woff2,eof,svg}'])
            .pipe(gulp.dest('./assets/admin/build/'));
    });

});

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix){

    mix.sass([
        './node_modules/animate.css/animate.min.css',
        './node_modules/animsition/dist/css/animsition.min.css',
        './node_modules/material-design-icons/iconfont/material-icons.css',
        './node_modules/codemirror/lib/codemirror.css',
        './node_modules/codemirror/addon/hint/show-hint.css',
        './node_modules/codemirror/addon/fold/foldgutter.css',
        './node_modules/codemirror/theme/rubyblue.css',
        './flexcms/packages/appverse.notifications/dist/notification-bar.css',
        './assets/admin/src/css/admin.scss',
        './assets/admin/src/css/font-awesome.min.css',
        './node_modules/chosen-js/chosen.css',
        './node_modules/angular-ui-tree/dist/angular-ui-tree.css',
        './node_modules/angular-material/angular-material.min.css',
        './node_modules/angular-timezone-selector/dist/angular-timezone-selector.min.css',
        './assets/admin/src/css/pe-icon-7-stroke.css',
        './node_modules/animate.css/animate.min.css',
        './node_modules/md-color-picker/dist/mdColorPicker.min.css',
        './node_modules/ui-cropper/compile/minified/ui-cropper.css',
        './assets/admin/src/css/scrollbars.scss'
    ], './assets/admin/build/app.css');

    //Admin scripts
    mix.scripts([
        './assets/admin/src/js/jquery.min.js', //Required for animsition
        './assets/admin/src/js/modernizr.custom.js',
        './node_modules/animsition/dist/js/animsition.min.js',

        './node_modules/jstz/dist/jstz.min.js',
        './node_modules/moment/min/moment.min.js',
        './node_modules/moment-timezone/builds/moment-timezone-with-data.min.js',
        './node_modules/chosen-js/chosen.jquery.js',
        './node_modules/lodash/dist/lodash.min.js',

        './node_modules/angular/angular.js',
        './node_modules/angular-animate/angular-animate.min.js',
        './node_modules/angular-aria/angular-aria.min.js',
        './node_modules/angular-material/angular-material.js',
        './node_modules/angular-animate/angular-animate.min.js',

        './node_modules/ng-file-upload/dist/ng-file-upload.js',
        './node_modules/angularjs-scroll-glue/src/scrollglue.js',
        './node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.js',

        './node_modules/codemirror/lib/codemirror.js',
        './node_modules/codemirror/addon/edit/closetag.js',
        './node_modules/codemirror/addon/edit/matchtags.js',
        './node_modules/codemirror/addon/display/autorefresh.js',
        './node_modules/codemirror/addon/fold/foldcode.js',
        './node_modules/codemirror/addon/fold/xml-fold.js',
        './node_modules/codemirror/addon/fold/foldgutter.js',
        './node_modules/codemirror/addon/hint/show-hint.js',
        './node_modules/codemirror/addon/hint/xml-hint.js',
        './node_modules/codemirror/addon/hint/html-hint.js',
        './node_modules/codemirror/mode/xml/xml.js',
        './node_modules/codemirror/mode/javascript/javascript.js',
        './node_modules/codemirror/mode/css/css.js',
        './node_modules/codemirror/mode/htmlmixed/htmlmixed.js',
        './flexcms/packages/angular-ui-codemirror/ui-codemirror.js',

        './flexcms/packages/tinymce/tinymce.min.js',
        './node_modules/angular-i18n/angular-locale_es-ec.js',
        './node_modules/angular-route/angular-route.js',
        './node_modules/angular-route-segment/build/angular-route-segment.js',
        './node_modules/angular-ui-tinymce/dist/tinymce.min.js',
        './node_modules/lodash/lodash.min.js',
        './node_modules/angular-timezone-selector/dist/angular-timezone-selector.min.js',

        './node_modules/angular-resource/angular-resource.js',
        './node_modules/angular-sanitize/angular-sanitize.js',
        './node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.js',
        './node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js',
        './node_modules/angular-drag-and-drop-lists/angular-drag-and-drop-lists.min.js',
        './node_modules/angular-ui-router/release/angular-ui-router.js',
        './node_modules/angular-ui-tree/dist/angular-ui-tree.min.js',
        './node_modules/tinycolor2/dist/tinycolor-min.js',
        './node_modules/md-color-picker/dist/mdColorPicker.min.js',
        './node_modules/md-color-picker/dist/mdColorPicker.min.js',
        './node_modules/ui-cropper/compile/minified/ui-cropper.js',
        './flexcms/packages/color-thief/dist/color-thief.min.js',
        './assets/admin/src/js/login.js'
    ], './assets/admin/build/app.js');

    //Login scripts
    mix.scripts([
        './assets/admin/src/js/modernizr.custom.js',
        './assets/admin/src/js/jquery.min.js', //Required for animsition
        './node_modules/animsition/dist/js/animsition.min.js',
        './node_modules/angular/angular.js',
        './node_modules/angular-aria/angular-aria.min.js',
        './node_modules/angular-material/angular-material.js',
        './node_modules/angular-animate/angular-animate.min.js',
        './node_modules/angular-spinner/dist/angular-spinner.min.js',
        './assets/admin/src/js/login.js'
    ], './assets/admin/build/login.js');

    //Copy the fonts
    mix.copyfonts();

});
