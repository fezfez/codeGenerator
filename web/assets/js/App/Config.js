{
    baseUrl: __BASEPATH__ + './assets/js/',
    paths: {
        App : "App/",
        Controllers : "App/Controllers",
        Corp : "App/Corp/",
        JQuery: "Vendor/jquery/dist/jquery.min",
        TwitterBootstrap: "Vendor/bootstrap/dist/js/bootstrap.min",
        Angular: "Vendor/angular/angular.min",
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
        'shCore': {
            //These script dependencies should be loaded before loading
            exports: 'SyntaxHighlighter'
        },
        'HighLighterPHP': {
            //These script dependencies should be loaded before loading
            deps: ['shCore']
        },
    }
}