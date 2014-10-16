define(function (require) {
    'use strict';

    var app               = require('App/App'),
        ContextHydrator   = require('Corp/Context/ContextHydrator'),
        GeneratorHydrator = require('Corp/Generator/GeneratorHydrator'),
        instance          = null;

    function GeneratorHydratorFactory($sce) {
        return {
            /*
             * @return {Corp/Generator/GeneratorHydrator}
             */
            getInstance : function () {
                if (instance === null) {
                    instance = new GeneratorHydrator($sce, new ContextHydrator());
                }

                return instance;
            }
        };
    }

    return app.factory('GeneratorHydratorFactory', ['$sce', GeneratorHydratorFactory]);
});