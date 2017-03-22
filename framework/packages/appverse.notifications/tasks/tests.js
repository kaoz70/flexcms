/*jshint node:true */
'use strict';

module.exports = function(grunt) {

    grunt.registerTask('test:unit:watch', [
        'karma:unitAutoWatch'
    ]);

    grunt.registerTask('test:unit:once', [
        'karma:unit'
    ]);

};
