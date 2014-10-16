define(function (require) {
    'use strict';

    var app                      = require('App/App'),
        GeneratorHydratorFactory = require('Corp/Generator/GeneratorHydratorFactory'),
        GeneratorDAO             = require('Corp/Generator/GeneratorDAO'),
        instance                 = null;

    function GeneratorDAOFactory($http, $q, $sce, GeneratorHydratorFactory) {
        return {
            /*
             * @return {Corp/Generator/GeneratorDAO}
             */
            getInstance : function () {
                if (instance === null) {
                    instance = new GeneratorDAO($http, $q, $sce, GeneratorHydratorFactory.getInstance());
                }

                return instance;
            }
        };
    }

    return app.factory('GeneratorDAOFactory', ['$http', '$q', '$sce', 'GeneratorHydratorFactory', GeneratorDAOFactory]);
});