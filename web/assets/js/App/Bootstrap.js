requirejs.config({
    //By default load any module IDs from js/lib
    baseUrl: __BASEPATH__ + './assets/js/',
    //except, if the module ID starts with 'app',
    //load it from the js/app directory. paths
    //config is relative to the baseUrl, and
    //never includes a '.js' extension since
    //the paths config could be for a directory.
    paths: {
        App : 'App/',
        Controllers : 'App/Controllers',
        Corp : 'App/Corp/',
        JQuery: 'Vendor/jquery/dist/jquery',
        TwitterBootstrap: 'Vendor/bootstrap/js/modal',
        TwitterBootstrapTooltip: 'Vendor/bootstrap/js/tooltip',
        Angular: 'Vendor/angular/angular',
        highlight: 'Vendor/highlightjs-amd/highlight.pack',
    },
    shim: {
        'TwitterBootstrap': {
            //These script dependencies should be loaded before loading
            deps: ['TwitterBootstrapTooltip'],
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

require(
    [
        'Angular',
        'App/App',
        'App/Directives/UnsafeModal',
        'App/Directives/Modal',
        'Controllers/GeneratorController',
        'Corp/File/FileDirective',
        'Corp/File/ViewFileDirective',
        'Corp/History/HistoryDirective',
        'Corp/Source/SourceDirective',
        'Corp/Generator/SearchGeneratorDirective'
    ],
    function (angular, app, controller) {
    app.init();
});