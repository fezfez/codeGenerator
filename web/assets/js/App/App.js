console.log('App/App out');
define(function() {
	
    var App = (function () {
        "use strict";

        var instance = null;

        function init() {
        	console.log('init app');
            var app = angular.module(
                'GeneratorApp', 
                []
            );
            angular.bootstrap(
                document,
                ['GeneratorApp']
            );
            return app;
        }

        if (instance === null) {
            instance = init();
        }
        return instance;
    })();

    return App;
});