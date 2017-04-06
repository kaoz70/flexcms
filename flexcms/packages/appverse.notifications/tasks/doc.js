/*jshint node:true */
'use strict';

module.exports = function(grunt) {

    grunt.registerTask('doc:main', [
        'clean:doc',
        'ngdocs'
    ]);

    grunt.registerTask('doc', [
        'doc:main',
        'open:doc',
        'connect:doc'
    ]);
};
