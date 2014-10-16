// Karma configuration
// Generated on Mon Mar 10 2014 21:52:15 GMT+0100 (CET)

module.exports = function(config) {
  config.set({

    // base path, that will be used to resolve files and exclude
    basePath: './web/assets/js/',

    // frameworks to use
    frameworks: ['jasmine', 'requirejs'],

    // list of files / patterns to load in the browser
    files: [
        {pattern: 'Vendor/angular/angular.js', included: false},
        {pattern: 'Vendor/angular-mocks/angular-mocks.js', included: false},
        {pattern: 'Vendor/jquery/dist/jquery.js', included: true},
        {pattern: 'Vendor/bootstrap/dist/js/bootstrap.js', included: true},
        {pattern: 'Vendor/highlightjs-amd/highlight.pack.js', included: false},
        {pattern: 'App/**/*.js', included: false},
        {pattern: 'Tests/App/**/*.test.js', included: false},
        {pattern: 'Tests/karma.main.js', included: true}
    ],

    // list of files to exclude
    exclude: [
      'App/Bootstrap.js'
    ],

    plugins:[
     'karma-requirejs',
     'karma-jasmine',
     'karma-chai',
     'karma-coverage',
     'karma-firefox-launcher'
     ],

    // test results reporter to use
    // possible values: 'dots', 'progress', 'junit', 'growl', 'coverage'
    reporters: ['progress', 'coverage'],

    preprocessors: {
        // source files, that you wanna generate coverage for
        // do not include tests or libraries
        // (these files will be instrumented by Istanbul)
        'App/**/*.js': ['coverage']
    },
    coverageReporter: {
        type : 'html',
        dir  : 'coverage-javascript/'
    },
    // enable / disable colors in the output (reporters and logs)
    colors: true,

    // enable / disable watching file and executing tests whenever any file changes
    autoWatch: true,

    captureTimeout : 60000,

    // Start these browsers, currently available:
    // - Chrome
    // - ChromeCanary
    // - Firefox
    // - Opera (has to be installed with `npm install karma-opera-launcher`)
    // - Safari (only Mac; has to be installed with `npm install karma-safari-launcher`)
    // - PhantomJS
    // - IE (only Windows; has to be installed with `npm install karma-ie-launcher`)
    
    browsers: ['Firefox'],

    // Continuous Integration mode
    // if true, it capture browsers, run tests and exit
    singleRun: false
  });
};
