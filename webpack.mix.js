const mix = require('laravel-mix');

const distDir = 'assets/admin';

mix.sass('./assets/admin/src/css/admin.scss', `./${distDir}/build/app.css`).sourceMaps();

// Admin scripts
mix.scripts([
    './assets/admin/src/js/jquery.min.js', // Required for animsition
    './assets/admin/src/js/modernizr.custom.js',
    './node_modules/animsition/dist/js/animsition.min.js',

    './node_modules/jstz/dist/jstz.min.js',
    './node_modules/moment/min/moment.min.js',
    './node_modules/moment-timezone/builds/moment-timezone-with-data.min.js',
    './node_modules/chosen-js/chosen.jquery.js',
    './node_modules/lodash/lodash.min.js',

    './node_modules/angular/angular.js',
    './node_modules/angular-animate/angular-animate.min.js',
    './node_modules/angular-aria/angular-aria.min.js',
    './node_modules/angular-material/angular-material.js',
    './node_modules/angular-animate/angular-animate.min.js',

    './node_modules/ng-file-upload/dist/ng-file-upload.js',
    './node_modules/angularjs-scroll-glue/src/scrollglue.js',
    './node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',

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

    './node_modules/angular-toastr/dist/angular-toastr.js',
    './node_modules/angular-toastr/dist/angular-toastr.tpls.js',

    './node_modules/tinymce/tinymce.min.js',
    './node_modules/angular-i18n/angular-locale_es-ec.js',
    './node_modules/angular-route/angular-route.js',
    './node_modules/angular-route-segment/build/angular-route-segment.js',
    './node_modules/angular-ui-tinymce/dist/tinymce.min.js',
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
    './node_modules/ui-cropper/compile/unminified/ui-cropper.js',
    './node_modules/color-thief/js/color-thief.js',
    './assets/admin/src/js/login.js',
], `./${distDir}/build/app.js`).sourceMaps();

// Login scripts
mix.scripts([
    './assets/admin/src/js/modernizr.custom.js',
    './assets/admin/src/js/jquery.min.js', // Required for animsition
    './node_modules/animsition/dist/js/animsition.min.js',
    './node_modules/angular/angular.js',
    './node_modules/angular-aria/angular-aria.min.js',
    './node_modules/angular-material/angular-material.js',
    './node_modules/angular-animate/angular-animate.min.js',
    './node_modules/angular-spinner/dist/angular-spinner.min.js',
    './assets/admin/src/js/login.js',
], `./${distDir}/build/login.js`);

// Copy some assets into dist folder
mix.copyDirectory('./node_modules/font-awesome/fonts', `${distDir}/fonts`);
mix.copyDirectory('./node_modules/material-design-icons/iconfont', `${distDir}/fonts`);
mix.copyDirectory('./node_modules/tinymce/skins', `${distDir}/build/skins`);
mix.copyDirectory('./node_modules/tinymce/themes', `${distDir}/build/themes`);

// Avoid Mix copying files (pictures, svg) automatically to css dist folder.
/* mix.options({
    processCssUrls: false,
});

//Process SASS components.
mix.sass('resources/scss/main.scss', `${dist_dir}/css/app.css`);

//Process Vue components
mix.js('resources/main.js', `${dist_dir}/js/app.js`);

//Copy images, fonts and other files
mix.copy('resources/images', `public/${dist_dir}/images`);
mix.copy('resources/fonts', `public/${dist_dir}/fonts`); */

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.preact(src, output); <-- Identical to mix.js(), but registers Preact compilation.
// mix.coffee(src, output); <-- Identical to mix.js(), but registers CoffeeScript compilation.
// mix.ts(src, output); <-- TypeScript support. Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.test');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.override(function (webpackConfig) {}) <-- Will be triggered once the webpack config object has been fully generated by Mix.
// mix.dump(); <-- Dump the generated webpack config object to the console.
// mix.extend(name, handler) <-- Extend Mix's API with your own components.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   terser: {}, // Terser-specific options. https://github.com/webpack-contrib/terser-webpack-plugin#options
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });
