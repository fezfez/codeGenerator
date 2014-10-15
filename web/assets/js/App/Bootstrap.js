requirejs.config({
    //By default load any module IDs from js/lib
    baseUrl: __BASEPATH__ + './assets/js/',
    //except, if the module ID starts with "app",
    //load it from the js/app directory. paths
    //config is relative to the baseUrl, and
    //never includes a ".js" extension since
    //the paths config could be for a directory.
    paths: {
        App : "App/",
        Controllers : "App/Controllers",
        Corp : "App/Corp/",
        JQuery: "Vendor/jquery/dist/jquery",
        TwitterBootstrap: "Vendor/bootstrap/js/modal",
        TwitterBootstrapTooltip: "Vendor/bootstrap/js/tooltip",
        Angular: "Vendor/angular/angular",
        shPHP: "Vendor/SyntaxHighlighter/scripts/shBrushPhp",
        SyntaxHighlighter: "Vendor/SyntaxHighlighter/scripts/shCore",
        XRegExp: "Vendor/SyntaxHighlighter/scripts/XRegExp"
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
        },
        'SyntaxHighlighter': {
            //These script dependencies should be loaded before loading
            exports: 'SyntaxHighlighter',
            deps: ['XRegExp']
        },
        'shPHP': {
            //These script dependencies should be loaded before loading
            deps: ['SyntaxHighlighter']
        },
    }
});

require(
    [
        'Angular',
        'App/App',
        "App/Directives/UnsafeModal",
        "App/Directives/Modal",
        'Controllers/GeneratorController',
        "Corp/File/FileDirective",
        "Corp/History/HistoryDirective",
        "Corp/Source/SourceDirective",
        "Corp/Generator/SearchGeneratorDirective",
        "SyntaxHighlighter"
    ],
    function (angular, app, controller) {
    app.init();
});