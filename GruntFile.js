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
        copy: {
          main: {
            files: [
              {
                  cwd: 'web/assets/js/Vendor/requirejs/',
                  src: 'require.js',
                  dest: 'web/assets/build/',
                  expand: true
              },
              {
                  cwd: 'web/assets/js/Vendor/pace/', 
                  src: 'pace.min.js',
                  dest: 'web/assets/build/',
                  expand: true
              }
            ]
          }
        }
    };

    grunt.task.registerTask('build', ['requirejs', 'copy']);

    grunt.initConfig(gruntConfig);

    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-requirejs');

};