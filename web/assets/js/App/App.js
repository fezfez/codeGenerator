define(["Angular"], function(angular) {
    var app = angular.module(
        'GeneratorApp', 
        []
    );

    app.init = (function () {
        angular.bootstrap(
            document,
            ['GeneratorApp']
        );
    });

    return app;
});