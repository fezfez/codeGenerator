module.exports = function (grunt) {
    __BASEPATH__ = '';
    var gruntConfig = {
        pkg: grunt.file.readJSON('package.json'),
        requirejs: {
            js: {
                options: {
                    optimize : 'uglify2',
                    preserveLicenseComments : false,
                    inlineText : true,
                    findNestedDependencies : true,
                    name : 'App/Bootstrap',
                    baseUrl: 'web/assets/js',
                    mainConfigFile: 'web/assets/js/App/Bootstrap.js',
                    out: 'web/assets/build/script.js',
                    generateSourceMaps: true
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
                  cwd: 'web/assets/js/Vendor/pace/', 
                  src: 'pace.min.js',
                  dest: 'web/assets/build/',
                  expand: true
              }
            ]
          }
        },
        uglify: {
            options: {
                  compress: {
                    global_defs: {
                      'DEBUG': false
                    },
                    dead_code: true
                  }
                },
            my_target: {
              files: {
                'web/assets/build/require.min.js': ['web/assets/js/Vendor/requirejs/require.js']
              }
            }
          }
    };

    grunt.task.registerTask('build', ['requirejs', 'copy', 'uglify']);

    grunt.initConfig(gruntConfig);

    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-requirejs');

};