define(['Angular'], function(angular) {

    var App = (function () {
        'use strict';

        var instance = null;

        function init() {
            var app = angular.module(
                'GeneratorApp', 
                []
            );

            app.init = function() {
                angular.bootstrap(
                    document,
                    ['GeneratorApp']
                );
            };

            return app;
        }

        if (instance === null) {
            instance = init();
        }

        return instance;
    })();

    return App;
}, function(err) {
    console.log(err);
});