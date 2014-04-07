__BASEPATH__ = '';

var tests = [];
// we want require to load our test(spec) files

for (var file in window.__karma__.files) {
  if (window.__karma__.files.hasOwnProperty(file)) {
    // simple pattern that matches our files
    // note that these files are available here 
    // because of our settings in the karma.conf.js files[]
    if (/(.+test)\.js$/.test(file)) {
    	
      tests.push(file);
    }
  }
}

var path = '';
 
if (typeof window.__karma__ !== 'undefined') {
  path += '/base/';
}
 
requirejs.config({
    //By default load any module IDs from js/lib
    baseUrl: 'base/',
    urlArgs: "t=" + (new Date()).getTime(),
    //except, if the module ID starts with "app",
    //load it from the js/app directory. paths
    //config is relative to the baseUrl, and
    //never includes a ".js" extension since
    //the paths config could be for a directory.
    paths: {
        App : "App",
        Controllers : "./App/Controllers",
        Corp : "App/Corp",
        JQuery: "Vendor/jquery-2.1.0.min",
        TwitterBootstrap: "Vendor/bootstrap.min",
        Angular: "Vendor/angular.min",
        AngularMocks: "Vendor/angular-mocks",
        HighLighterPHP: "Vendor/shBrushPhp",
        shCore: "Vendor/shCore"
    },
    shim: {
        'TwitterBootstrap': {
            //These script dependencies should be loaded before loading
            deps: ['JQuery'],
            //Once loaded, use the global 'TwitterBootstrap' as the
            //module value.
            exports: 'TwitterBootstrap'
        },
        'Angular': {
            //These script dependencies should be loaded before loading
            deps: ['JQuery'],
            //Once loaded, use the global 'GoogleJSAPI' as the
            //module value.
            exports: 'angular'
        },
        'AngularMocks': {
            //These script dependencies should be loaded before loading
            deps: ['Angular'],
            //Once loaded, use the global 'GoogleJSAPI' as the
            //module value.
            exports: 'angular'
        },
        'shCore': {
            //These script dependencies should be loaded before loading
            exports: 'SyntaxHighlighter'
        },
        'HighLighterPHP': {
            //These script dependencies should be loaded before loading
            deps: ['shCore']
        },
    },
    
    // ask Require.js to load these files (all our tests)
    deps: tests,
 
    // start test run, once Require.js is done
    // the original callback here was just:
    // callback: window.__karma__.start
    // I was running into issues with jasmine-jquery though
    // specifically specifying where my fixtures were located
    // this solved it for me.
    callback: function(){
        window.__karma__.start();
    }
});