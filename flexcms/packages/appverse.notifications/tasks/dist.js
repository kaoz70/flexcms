/*jshint node:true */
'use strict';

module.exports = function(grunt) {

    grunt.registerTask('default', [
        'dist'
    ]);

    grunt.registerTask('dist', [
        'concurrent:dist',
        'make_dist_and_test'
    ]);

    grunt.registerTask('make_dist_and_test', [
        'dist:make'
    ]);

    grunt.registerTask('dist:make', [
        'clean:dist',
        'copy',
        'concat',
        'ngAnnotate',
        'uglify',
        'sass'
    ]);
};
