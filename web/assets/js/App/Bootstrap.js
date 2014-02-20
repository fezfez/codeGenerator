requirejs.config({
    //By default load any module IDs from js/lib
    baseUrl: './assets/js/',
    //except, if the module ID starts with "app",
    //load it from the js/app directory. paths
    //config is relative to the baseUrl, and
    //never includes a ".js" extension since
    //the paths config could be for a directory.
    paths: {
        App : "App/",
        Controllers : "App/Controllers",
        Corp : "App/Corp/",
        JQuery: "Vendor/jquery-2.1.0.min",
        TwitterBootstrap: "Vendor/bootstrap.min",
        Angular: "Vendor/angular",
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
        }
    }
});

require(['Angular', 'App/App', 'Controllers/GeneratorController', "App/Directives/File"], function (angular, app, controller) {
    app.init();
});