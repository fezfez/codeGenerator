module.exports = function (grunt) {
    __BASEPATH__ = '';
    var gruntConfig = {
        pkg: grunt.file.readJSON('package.json'),
        requirejs: {
            js: {
                options: {
                    optimize: "uglify",
                    name : 'App/Bootstrap',
                    baseUrl: 'web/assets/js',
                    mainConfigFile: "web/assets/js/App/Bootstrap.js",
                    out: 'web/assets/build/script.js'
                }
            },
            css: {
                options: {
                  optimizeCss: 'uglify',
                  cssIn: 'web/assets/css/main.css',
                  out: 'web/assets/build/style.css'
                }
              }
        },
    };

    grunt.initConfig(gruntConfig);

    grunt.loadNpmTasks('grunt-contrib-requirejs');

};